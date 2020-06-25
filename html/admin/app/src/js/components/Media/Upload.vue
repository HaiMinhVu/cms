<template>
  <div class="mt-4">
    <BJumbotron class="mb-0" @drop.prevent="dropHandler" @dragover.prevent>
    	<div class="row">
    		<div class="col text-center">
    			<p class="">Drop files to upload or</p>
    			<BButton variant="primary" @click="browse">
    				<BIconFolder />
    				Browse
    			</BButton>
    		</div>
    	</div>
	</BJumbotron>
	<BFormFile ref="inputFormEl" class="btn btn-primary hidden-file-input" placeholder="" size="sm" no-drop @input="inputHandler" plain multiple />
	<div v-if="hasFiles">
		<div class="row">
			<BTable striped :items="uploadedItems" :fields="fields">
				<template v-slot:cell(preview)="row">
					<BImg width="200" height="auto" :src="row.item.base64image" v-if="row.item.isImage" />
                    <div class="text-center" v-else>
                        <div class="fa fa-file"></div>
                    </div>
				</template>
				<template v-slot:cell(type)="row" v-if="showTypeSelector">
					<BFormGroup label="Type">
						<BFormSelect @change="updateData($event, key(row), 'type')" :options="typeOptions"></BFormSelect>
					</BFormGroup>
				</template>
				<template v-slot:cell(brand)="row">
					<div class="row">
						<div class="col">
							<BFormGroup label="Brand">
								<BFormSelect @change="updateData($event, key(row), 'brand')" :options="brandOptions"></BFormSelect>
							</BFormGroup>
						</div>
						<div class="col-auto align-self-center">
							<BIconDashCircleFill size="lg" variant="danger" @click="remove(row.item.name)" />
						</div>
					</div>
				</template>
			</BTable>
			<hr />
		</div>
		<transition name="component-fade" mode="out-in">
			<div class="row" v-if="showValidationWarning">
				<div class="col">
					<BAlert show variant="danger">Please select a <strong>Type</strong> and <strong>Brand</strong> for each item</BAlert>
					<hr />
				</div>
			</div>
		</transition>
		<div class="row">
			<div class="col text-right">
				<BButton :disabled="uploadButtonDisabled" variant="primary" @click="upload">
					<BIconCloudUpload :class="{ 'temp-hide': uploading }" />
					<BSpinner small v-if="uploading" variant="light" />
					Upload
				</BButton>
			</div>
		</div>
	</div>
  </div>
</template>

<script>
	import {
		BButton,
		BFormSelect,
		BFormGroup,
		BFormFile,
		BJumbotron,
		BListGroup,
		BListGroupItem,
		BTable,
		BIconCloudUpload,
		BIconFolder,
        BIconFiles,
		BImg,
		BSpinner,
		BIconDashCircleFill,
		BAlert
	} from 'bootstrap-vue';
	import { find, filter, map, concat, snakeCase, remove } from 'lodash';

	export default {
		props: {
			httpClient: {
				type: Object
			},
			brandList: {
				type: Array
			},
			types: {
				Object
			},
			forceType: {
				type: String,
				default: null
			}
		},
	    data() {
	        return {
	        	fields: ['preview', 'name', 'type', 'brand'],
	        	fileList: [],
	        	uploadedItems: [],
	        	data: {},
	        	uploading: false,
	        	showValidationWarning: false
	        };
	    },
	    created() {
	    	if(this.forceType) {
	    		this.fields = this.fields.filter(field => field != 'type');
	    	}
	    },
	    methods: {
	        parseUploadedItems(files) {
	        	return Promise.all(
		        	files.map(async file => {
                        let isImage = this.isImage(file);
		    			return {
		    				name: file.name,
                            isImage: isImage,
		    				base64image: isImage ? await this.toBase64(file) : null
		    			}
	    			})
	        	);
	        },
	        async inputHandler(files) {
	        	const newFiles = map(files, file => file);
	        	this.handleData(newFiles);
	        	this.fileList = concat(this.fileList, newFiles);
	        	this.uploadedItems = await this.parseUploadedItems(this.fileList);
	        },
	        handleData(newData) {
	        	newData.map(file => {
	        		const userKey = snakeCase(file.name);
	        		if(!(userKey in this.data)) {
						this.data[userKey] = { file: file };
						if(this.forceType) {
							this.data[userKey]['type'] = this.forceType;
						}
	        		}
	        	});
	        },
            isImage(file) {
                return ['image/gif', 'image/jpeg', 'image/png'].includes(file.type);
            },
	        dropHandler(e) {
	        	const { files } = e.dataTransfer;
	        	this.inputHandler(files);
	        },
	        browse() {
	        	this.$refs.inputFormEl.$refs.input.click();
	        },
	        toBase64(file) {
	        	return new Promise((resolve, reject) => {
				    const reader = new FileReader();
				    reader.readAsDataURL(file);
				    reader.onload = () => resolve(reader.result);
				    reader.onerror = error => reject(error);
				});
		    },
	        getBase64(file) {
			   const reader = new FileReader;
			   reader.readAsDataURL(file);
			   reader.onload = () => {
			     return reader.result;
			   };
			   reader.onerror = err => {
			     console.error(`Error: ${err}`);
			   };
			},
			async upload() {
				this.uploading = true;
				const canUpload = this.canUpload();
				this.showValidationWarning = !canUpload;

				if(canUpload) {
					await this.handleUpload();
					this.$root.$bvToast.toast(`Uploaded ${this.fileList.length} files`, {
			          title: 'Featured Product List',
			          autoHideDelay: 3000,
			          appendToast: true,
			          toaster: 'b-toaster-bottom-left',
			          variant: 'success'
			        });
			        this.$emit('changeTab', 'media_library');
				}
				this.uploading = false;
			},
			key(row) {
				return snakeCase(row.item.name);
			},
			updateData(value, key, type) {
				if(!(key in this.data)) this.data[key] = {};
				if(!(type in this.data[key])) this.data[key][type] = {};
				this.data[key][type] = value;

				if(this.showValidationWarning) {
					this.showValidationWarning = !this.canUpload();
				}
			},
			handleUpload() {
				return Promise.all(
					map(this.data, async (item, key) => {
						const formData = new FormData;

						map(item, (value, itemKey) => {
							formData.append(itemKey, value);
						});

						const { data } = await this.httpClient.post('file', formData, {
							headers: {
								'Content-Type': 'multipart/form-data'
							}
						});
					})
				);
			},
			canUpload() {
	    		if(this.hasFiles) {
	    			return !find(this.data, (item, key) => {
	    				return Object.values(item).length < 3;
	    			});
	    		}
	    		return false;
	    	},
	    	remove(name) {
	    		['fileList', 'uploadedItems'].map(property => {
	    			this[property] = this[property].filter(item => {
	    				return name != item.name;
	    			});
	    		});
	    		delete this.data[snakeCase(name)];
	    	}
	    },
	    computed: {
	    	hasFiles() {
	    		return this.files.length > 0;
	    	},
	    	files() {
	    		return map(this.fileList, file => file);
	    	},
	    	brandOptions() {
	    		return this.brandList.map(brand => {
	    			return {
	    				value: brand,
	    				text: brand
	    			};
	    		});
	    	},
	    	typeOptions() {
	    		return map(this.types, (type, key) => {
	    			return {
	    				text: type,
	    				value: key
	    			}
	    		});
	    	},
	    	uploadButtonDisabled() {
	    		return this.uploading || this.showValidationWarning;
	    	},
	    	showTypeSelector() {
	    		return !this.forceType;
	    	}
		},
		filters: {
			snakeCase
		},
	    components: {
	    	BButton,
	    	BFormSelect,
	    	BFormGroup,
	    	BFormFile,
	    	BJumbotron,
	    	BListGroup,
	    	BListGroupItem,
	    	BTable,
	    	BIconCloudUpload,
	    	BIconFolder,
            BIconFiles,
	    	BImg,
	    	BSpinner,
	    	BIconDashCircleFill,
	    	BAlert
	    }
	}
</script>

<style>
	.hidden-file-input {
		opacity: 0;
		pointer-events: none;
		height: 0;
		width: 0;
		padding: 0;
		margin: 0;
		border: none;
	}
	.temp-hide {
		opacity: 0;
		margin-left: -20px;
	}
    table td {
        vertical-align: middle !important;
    }
    .fa-file {
        font-size: 200%;
    }
</style>
