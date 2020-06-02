<template>
	<BSidebar lazy id="sidebar-right" :title="item.file_name" width="600px" right shadow :visible="showSidebar" @change="sidebarStateChanged">
      <div class="m-4">
      	<BImg :src="item.url" fluid thumbnail v-if="item.is_image"></BImg>    
		<div v-else>
			<i class="fa fa-file"></i>
		</div>
	    <div class="mt-2 mb-2">
	    	<BFormGroup label="Display Name">
		    	<BFormInput v-model="name" />
		    </BFormGroup>
		    <BFormGroup label="Brand">
		    	<BFormSelect disabled v-model="brand">
		    		<BFormSelectOption v-for="(brand, key) in brands" :value="brand" :key="`brand-key-${key}`">{{ brand }}</BFormSelectOption>
		   		</BFormSelect>
		    </BFormGroup>
		    <BFormGroup label="Description">
		    	<BFormTextarea rows="5" v-model="description"></BFormTextarea>
		    </BFormGroup>
	    </div>
	    <hr />
	    <BButton size="sm" variant="primary" :disabled="cannotSave" @click="save">
	    	<BIconCloudUpload></BIconCloudUpload>
	    	Save
	    </BButton>
      </div>
    </BSidebar>
</template>

<script>
	
import {
		BButton,
		BIconCloudUpload,
		BFormGroup,
		BFormInput,
		BFormSelect,
		BFormSelectOption,
		BFormTextarea,
		BSidebar,
		BImg
	} from 'bootstrap-vue';
	import { clone, isEqual } from 'lodash';

	export default {
		props: {
			item: {
				type: Object,
				default: {}
			},
			showSidebar: {
				type: Boolean,
				default: false
			}
		},	
		data() {
			return {
				clonedItem: {},
				index: null,
				show: false
			}
		},
		methods: {
			async save() {
				this.$emit('saved', { ...this.clonedItem });
			},
			sidebarStateChanged(state) {
				this.$emit('change', state);
			}
		},
		computed: {
			brands() {
				return [this.brand];
			},
			hasBeenEdited() {
				const editedCount = ['display_name', 'description'].filter(key => {
					return this.item[key] != this.clonedItem[key];
				}).length;
				return editedCount > 0;
			},
			cannotSave() {
				return !this.hasBeenEdited;
			},
			brand: {
				get() {
					return this.clonedItem.brand;
				},
				set(val) {	
					this.$set(this.clonedItem, 'brand', val);
				}
			},
			description: {
				get() {
					return this.clonedItem.description;
				},
				set(val) {
					this.$set(this.clonedItem, 'description', val);
				}
			},
			name: {
				get() {
					return this.clonedItem.display_name;
				},
				set(val) {
					this.$set(this.clonedItem, 'display_name', val);
				}
			}
		},
		watch: {
			showSidebar(newVal) {
				if(newVal) {
					this.clonedItem = clone(this.item);
				}
			}
		},
		components: {
			BButton,
			BIconCloudUpload,
			BFormGroup,
			BFormInput,
			BFormSelect,
			BFormSelectOption,
			BFormTextarea,
			BSidebar,
			BImg
		}
	}
</script>

<style scoped lang="scss">
	.item-list {
		.img-thumbnail {
			height: 100%;
			width: 100%;
			// max-width: 150;
			// max-height: 150px;
			display: flex;
			justify-content: center;
			align-items: center;
			transition: transform 200ms;
			> div {
				display: flex;
				justify-content: center;
				align-items: center;
			}
			> * {
				width: 20vw;
				height: 20vw;
				max-width: 100%;
				max-height: 150px;
				object-fit: contain;
			}
			&:hover {

			}
		}
		.fa-file {
			font-size: 4em;
		}
		@media screen and (min-width: 575px) {
			.img-thumbnail {
				> * {
					width: 11vw;
					height: 11vw;
				}
			}
		}
		@media screen and (min-width: 990px) {
			.img-thumbnail {
				> * {
					width: 8vw;
					height: 8vw;
				}
			}
		}
	}
	.is-selected {
		outline: 4px solid #007bff;
	}
</style>