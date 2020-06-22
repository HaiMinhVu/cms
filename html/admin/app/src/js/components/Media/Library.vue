<template>
	<section>
		<div class="row mt-4">
			<div class="col-md">
				<BFormGroup class="mb-0" label="Search">
					<BFormInput v-model="searchQuery" debounce="500" />
				</BFormGroup>
			</div>
			<div class="col-md" v-if="showTypeSelector">
				<BFormGroup class="mb-0" label="Type">
					<BFormSelect v-model="selectedType">
						<BFormSelectOption value="all" key="type-key-all">All</BFormSelectOption>
						<BFormSelectOption v-for="(type, key) in types" :value="key" :key="key">{{ type }}</BFormSelectOption>
					</BFormSelect>
				</BFormGroup>
			</div>
			<div class="col-md">
				<BFormGroup class="mb-0" label="Brand">
					<BFormSelect v-model="selectedBrand">
						<BFormSelectOption value="all" key="brand-key-all">All</BFormSelectOption>
						<BFormSelectOption v-for="(brand, key) in brandList" :value="brand" :key="`brand-key-${key}`">{{ brand }}</BFormSelectOption>
					</BFormSelect>
				</BFormGroup>
			</div>
		</div>
		<hr />
		<div v-if="hasItems">
			<div class="row mb-1">
				<div class="col">
					<span v-if="showPagination">
						<BPagination v-model="currentPage" :per-page="perPage" :total-rows="totalRows" v-if="hasItems"></BPagination>
					</span>
				</div>
				<div class="col text-right">
					Found {{ totalRows }} Items
				</div>
			</div>
			<div class="row item-list">
				<div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3" v-for="item in items" :key="item.id">
					<div :id="`media-library-item-${item.id}`" :class="['img-thumbnail', { 'is-selected': isSelected(item.id) }]" @click="itemAction(item)">
						<img :src="item.url" width="100" v-if="item.is_image" />
						<div v-else>
							<div class="text-center" style="max-width: 100%;">
								<div class="fa fa-file pb-1"></div>
								<small class="text-truncate" style="display: block;max-width: 100%;">{{ item.file_name }}</small>
							</div>
						</div>
					</div>
					<BTooltip variant="primary" :target="`media-library-item-${item.id}`" triggers="hover">{{ item.file_name }}</BTooltip>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<span v-if="showPagination">
						<BPagination v-model="currentPage" :per-page="perPage" :total-rows="totalRows" v-if="hasItems"></BPagination>
					</span>
				</div>
				<div class="col text-right" v-if="!editMode">
					<BButton :disabled="!hasSelectedMediaItems" :variant="selectedButtonVariant" @click="selectedAction">
						{{ selectedMediaItems.length }} Selected
					</BButton>
				</div>
			</div>
			<BSidebar lazy id="sidebar-right" :title="editItem.file_name" width="600px" right shadow v-model="showSidebar">
		      <div class="m-4" v-click-outside="hideSidebar">
		      	<BImg :src="editItem.url" fluid thumbnail v-if="editItem.is_image"></BImg>
				<div v-else>
					<i class="fa fa-file"></i>
				</div>
			    <div class="mt-2 mb-2">
			    	<BFormGroup label="Display Name">
				    	<BFormInput v-model="updatedDisplayName" />
				    </BFormGroup>
				    <BFormGroup label="Brand">
				    	<BFormSelect disabled v-model="editSelectedBrand">
				    		<BFormSelectOption v-for="(brand, key) in brandList" :value="brand" :key="`brand-key-${key}`">{{ brand }}</BFormSelectOption>
				   		</BFormSelect>
				    </BFormGroup>
				    <BFormGroup label="Description">
				    	<BFormTextarea rows="5" v-model="updatedDescription"></BFormTextarea>
				    </BFormGroup>
			    </div>
			    <hr />
			    <BButton size="sm" variant="primary" @click="save">
			    	<BIconCloudUpload></BIconCloudUpload>
			    	Save
			    </BButton>
		      </div>
		    </BSidebar>
		</div>
		<div v-else class="m-5">
			<h4>No media items matching filters</h4>
		</div>
	</section>
</template>

<script>
	import {
		BButton,
		BCard,
		BIconCloudUpload,
		BFormGroup,
		BFormInput,
		BFormSelect,
		BFormSelectOption,
		BFormTextarea,
		BPagination,
		BSidebar,
		BTooltip,
		BImg,
		BToast
	} from 'bootstrap-vue';
	import { isEmpty, xor } from 'lodash';
	import ClickOutside from 'vue-click-outside'

	export default {
		props: {
			httpClient: {
				type: Object
			},
			brandList: {
				type: Array
			},
			types: {
				type: Object
			},
			editMode: Boolean,
			singleSelector: Boolean,
			perPage: {
				type: Number,
				default: 48
			},
			forceType: {
				type: String,
				default: null
			}
		},
		created() {
			if(this.forceType) {
				this.selectedType = this.forceType;
			}
			this.getInitialData();
		},
		data() {
			return {
				selectedType: 'all',
				selectedBrand: 'all',
				searchQuery: null,
				editItem: {},
				items: [],
				currentPage: 1,
				totalRows: 0,
				totalPages: 1,
				showSidebar: false,
				editSelectedBrand: null,
				togglingSidebar: false,
				updatedDisplayName: null,
				updatedDescription: null,
				selectedMediaItems: []
			}
		},
		methods: {
			editItemFileNameChanged() {

			},
			async save() {
				await this.httpClient.put(`file/${this.editItem.id}`, {
					description: this.updatedDescription,
					display_name: this.updatedDisplayName
				});

				this.$root.$bvToast.toast(`${this.editItem.file_name} saved`, {
		          title: 'Featured Product List',
		          autoHideDelay: 3000,
		          appendToast: true,
		          toaster: 'b-toaster-bottom-left',
		          variant: 'success'
		        });
		        this.toggleSidebar();
			},
			async getInitialData() {
				this.getData();
			},
			async getData() {
				const options = {
					params: {
						limit: this.perPage,
						page: this.currentPage
					}
				};

				if(this.hasSelection) {
					options.params.type = this.selectedType;
				}

				if(this.hasBrand) {
					options.params.brand = this.selectedBrand;
				}

				if(this.hasSearchQuery) {
					options.params.search = this.searchQuery;
				}

				const { data } = await this.httpClient.get('file', options);
				this.$set(this, 'items', data.data);
				this.$set(this, 'totalRows', data.total);
				this.$set(this, 'totalPages', data.pages);
			},
			async getNewData() {
				this.getData();
				this.$set(this, 'currentPage', 1);
			},
			setDefaults() {

			},
			itemAction(item) {
				if(this.editMode) {
					this.editItemAction(item);
				} else {
					this.selectItemAction(item);
				}
			},
			selectItemAction(item) {
				const newItem = [item.id];
				const selectedMediaItems = this.singleSelector ? newItem : xor(this.selectedMediaItems, newItem);
				this.selectedMediaItems = selectedMediaItems;
			},
			editItemAction(item) {
				if(!this.showSidebar) {
					this.showSidebar = true;
					this.editItem = item;
					this.editSelectedBrand = item.brand;
					this.updatedDisplayName = this.editItem.display_name ? this.editItem.display_name : this.editItemFileName;
					this.updatedDescription = this.editItem.description;
				}
			},
			toggleSidebar() {
				this.showSidebar = !this.showSidebar;
			},
			hideSidebar() {
				if(!this.togglingSidebar) {
					this.showSidebar = false;
				}
			},
			isSelected(itemId) {
				return this.selectedMediaItems.includes(itemId);
			},
			selectedAction() {
				this.$emit('selectedAction', [...this.selectedMediaItems]);
			}
		},
		computed: {
			hasItems() {
				return this.items.length > 0;
			},
			hasSearchQuery() {
				return !isEmpty(this.searchQuery);
			},
			hasSelection() {
				return this.selectedType && this.selectedType != 'all';
			},
			hasBrand() {
				return this.selectedBrand && this.selectedBrand != 'all';
			},
			hasEditItem() {
				return !isEmpty(this.editItem);
			},
			hasSelectedMediaItems() {
				return this.selectedMediaItems.length > 0;
			},
			showTypeSelector() {
				return !this.forceType;
			},
			showPagination() {
				return this.hasItems && this.totalPages > 1;
			},
			editItemFileName() {
				if(this.hasEditItem) {
					const fullFileName = this.editItem.file_name;
					const fileNameArray = fullFileName.split('.').slice(0,-1).join('.');
					return fileNameArray;
				}
				return ''
			},
			selectedButtonVariant() {
				return (this.hasSelectedMediaItems) ? 'primary' : 'secondary';
			}
		},
		watch: {
			selectedType() {
				this.getNewData();
			},
			selectedBrand() {
				this.getNewData();
			},
			currentPage() {
				this.getData();
			},
			searchQuery() {
				this.getNewData();
			},
			showSidebar(newVal) {
				this.togglingSidebar = true;
				setTimeout(() => {
					this.togglingSidebar = false;
				}, 100);
			}
		},
		components: {
			BButton,
			BIconCloudUpload,
			BCard,
			BFormGroup,
			BFormInput,
			BFormSelect,
			BFormSelectOption,
			BFormTextarea,
			BPagination,
			BSidebar,
			BTooltip,
			BImg,
			BToast
		},
		directives: {
			ClickOutside
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
				height: 20vw;
				max-width: 100%;
				max-height: 150px;
				object-fit: contain;
			}
			&:hover {

			}
			.fa-file {
				font-size: 2em;
			}
		}
		@media screen and (min-width: 575px) {
			.img-thumbnail {
				> * {
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
				.fa-file {
					font-size: 3em;
				}
			}
		}
		@media screen and (min-width: 1200px) {
			.img-thumbnail {
				.fa-file {
					font-size: 4em;
				}
			}
		}
	}
	.is-selected {
		outline: 4px solid #007bff;
	}
</style>
