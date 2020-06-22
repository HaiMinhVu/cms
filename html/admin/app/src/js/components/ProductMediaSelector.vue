<template>
	<section>
		<div class="row mb-3">
			<div class="col">
				<h2>{{ title }}</h2>
			</div>
			<div class="col text-right">
				<BButtonGroup>
					<BButton @click="openModal" variant="secondary"><BIconImages /> Select Images</BButton>
					<BButton @click="save" variant="primary" :disabled="cannotSave"><BIconCloudUpload /> Save</BButton>
				</BButtonGroup>
			</div>
		</div>
		<table class="table" v-if="showList">
		  <thead>
		    <tr>
		      <th scope="col" v-for="field in fields">{{ field | toTitle }}</th>
		    </tr>
		  </thead>
		  	<draggable 
		  		v-model="selectedItems" 
		  		tag="tbody" 
		  		draggable=".draggable-item" 
		  		handle=".draggable-handle"
		  		v-bind="dragOptions"
		  	>
			    <tr v-for="(item, idx) in selectedItems" class="draggable-item" :key="`${item.id}_${idx}`">
			      <td>
			      	<span class="draggable-handle p-2"><BIconList class="test" /></span>
			      </td>
			      <td>
			      	<BImgLazy width="75" height="75" thumbnail :src="item.url" />
			      </td>
			      <td>
			      	<p class="mb-0">{{ item.file_name }}</p>
			      </td>
			      <td v-if="isImageType">
			      	<BFormRadio name="mainImageRadio" v-model="mainImageId" :value="item.id" />
			      </td>
			      <td>
			      	<BIconPencilSquare variant="primary" size="lg" scale="1.2" @click="edit(item)" />
			      </td>
			      <td>
			      	<BIconTrash variant="danger" size="lg" scale="1.2" @click="remove(item.id)" /></td>
			    </tr>
		  	</draggable>
		</table>
		<div v-if="isEmpty">
			<hr />
			<BJumbotron :lead="`No ${mediaType}s assigned`" />
		</div>
		<BTable :items="selectedItems" :fields="fields" v-if="false">
			<template v-slot:cell(image)="row">
				<div class="row align-items-center">
					<div class="col-auto">
						<BIconList />
					</div>
					<div class="col">
						<BImgLazy width="75" height="75" thumbnail :src="row.item.url" />
					</div>
				</div>
			</template>
			<template v-slot:cell(name)="row">
				<p class="mb-0">{{ row.item.file_name }}</p>
			</template>
			<template v-slot:cell(main_image)="row">
				<div class="row">
					<div class="col">
						<BFormRadio name="mainImageRadio" v-model="mainImageId" :value="row.item.id" />
					</div>
				</div>
			</template>
			<template v-slot:cell(remove)="row">
				<BIconDashCircleFill variant="danger" size="lg" @click="remove(row.item.id)" />
			</template>
		</BTable>
		<BModal hide-footer size="xl" title="Media Browser" v-model="modalVisible">
			<MediaBrowser per-page="24" force-type="image" :edit-mode="false" @selected="selected" />
		</BModal>
		<SidebarEditor :item="editItem" :show-sidebar="showSidebar" @saved="itemDetailsSaved" @change="sidebarChange" />
	</section>
</template>

<script>
	import {
		BAvatar,
		BButton,
		BModal,
		BImgLazy,
		BJumbotron,
		BIconImages,
		BIconList,
		BIconPencilSquare,
		BIconCloudUpload,
		BIconDashCircleFill,
		BIconTrash,
		BFormRadio,
		BTable,
		BButtonGroup
	} from 'bootstrap-vue';
	import MediaBrowser from './MediaBrowser.vue';
	import httpClient from '../httpClient';
	import { find, findIndex, uniqBy, upperFirst, words } from 'lodash';
	import draggable from 'vuedraggable';
	import SidebarEditor from './Media/SidebarEditor.vue';

	export default {
		props: { 
			productId: String,
			title: {
				type: String,
				default: 'Media Selector'
			},
			mediaType: {
				type: String,
				default: 'image'
			}
		},
		data() {
			return {
				modalVisible: false,
				selectedItemIds: [],
				selectedItems: [],
				fields: ['', 'image', 'name', 'main_image', 'edit', 'remove'],
				httpClient: null,
				mainImageId: null,
				saveLoading: false,
				showSidebar: false,
				editItem: {},
				loaded: false
			}
		},	
		async created() {
			this.httpClient = new httpClient;
			await this.getAllItems();
			this.loaded = true;
			if(!this.isImageType) {
				this.fields = this.fields.filter(field => field != 'main_image');
			}
		},
		methods: {
			async getAllItems() {
				const { data } = await this.httpClient.get(`product/${this.productId}/${this.mediaType}`); 
				if(this.isImageType) {
					this.getAllImageItems(data);
				} else if(this.isReticleType) {
					this.getAllReticleItems(data);
				}
			},
			async getAllImageItems(data) {
				this.selectedItems = [];
				this.addAsMainImage(data.main_image);
				this.addToSelected(data.images);
			},
			async getAllReticleItems(data) {
				this.selectedItems = [];
				this.addToSelected(data.reticles);
			},
			updateSingleItem(item) {
				const index = findIndex(this.selectedItems, selectedItem => selectedItem.id == item.id);
				if(index > -1) {
					this.selectedItems[index] = item;
				}
			},
			sidebarChange(state) {
				this.showSidebar = state;
			},
			openModal() {
				this.modalVisible = true;
			},
			edit(item) {
				this.editItem = item;
				this.showSidebar = true;
			},
			selected(items) {
				this.selectedItemIds = items;
				this.modalVisible = false;
				this.getItems(items);
			},
			addAsMainImage(imageObject) {
				if(imageObject) {
					this.addToSelected([imageObject]);
					this.mainImageId = imageObject.id;
				}
			},
			addToSelected(data) {
				this.selectedItems = uniqBy([...this.selectedItems, ...data], 'id');
			},
			async getItems() {
				const { data } = await this.httpClient.get('file', { params: { ids: this.selectedItemIds } });
				this.addToSelected(data.data);
			},
			remove(id) {
				this.selectedItems = this.selectedItems.filter(item => item.id != id);
			},
			getSaveData() {
				if(this.isImageType) {
					return this.getImageSaveData();
				} else if(this.isReticleType) {
					return this.getReticleSaveData();
				}
			},
			getImageSaveData() {
				return {
					product_id: this.productId,
					main_image: this.mainImage,
					images: this.selectedItems
				}
			},
			getReticleSaveData() {
				return {
					product_id: this.productId,
					reticles: this.selectedItems
				}
			},
			async save() {
				this.saveLoading = true;
				const saveData = this.getSaveData();
				const { data } = await this.httpClient.post(`product/${this.productId}/${this.mediaType}`, saveData);
				this.saveLoading = false;
				this.showImagesToast();
			},
			showToast(body, title = null) {
				const conf = {
		          autoHideDelay: 3000,
		          appendToast: true,
		          toaster: 'b-toaster-bottom-left',
		          variant: 'success'
		        };
		        if(title) conf.title = title;
				this.$root.$bvToast.toast(body, conf);
			},
			showImagesToast() {
				this.showToast(`${this.imageCount} ${this.mediaType}s synced`, `Product ${this.typeTitle}s`);
			},
			async itemDetailsSaved(newItem) {
				await this.httpClient.put(`file/${newItem.id}`, newItem);
				this.updateSingleItem(newItem);
				this.showToast('Details Saved', `Product ${this.typeTitle}`);
				this.showSidebar = false;
			}
		},
		computed: {
			hasSelectedItems() {
				return this.selectedItems.length > 0;
			},
			hasMainImage() {
				return !!this.mainImageId && this.hasSelectedItems;
			},
			isEmpty() {
				return this.loaded && !this.hasSelectedItems;
			},
			showList() {
				return this.hasSelectedItems;
			},
			isImageType() {
				return this.mediaType == 'image';
			},
			isReticleType() {
				return this.mediaType == 'reticle';
			},
			mainImage() {
				if(this.hasMainImage) {
					return find(this.selectedItems, item => {
						return item.id == this.mainImageId;
					});
				}
				return null;
			},
			imageCount() {
				return this.selectedItems.length;
			},
			selectedItemsFiltered() {
				if(this.hasMainImage) {
					return this.selectedItems.filter(item => {
						return item.id != this.mainImageId;
					});
				}
				return this.selectedItems;
			},
			cannotSave() {
				return this.saveLoading;
			},
			dragOptions() {
		      return {
		        animation: 200,
		        group: "description",
		        disabled: false,
		        ghostClass: "ghost",
		        forceFallback: true
		      }
		    },
		    typeTitle() {
		    	return upperFirst(this.mediaType);
		    }
		},
		filters: {
			toTitle(str) {
				const titleArray = words(str).map(word => upperFirst(word));
				return titleArray.join(' ');
			}
		},
		components: {
			BAvatar,
			BButton,
			BModal,
			BJumbotron,
			BImgLazy,
			BIconImages,
			BIconList,
			BIconPencilSquare,
			BIconCloudUpload,
			BIconDashCircleFill,
			BIconTrash,
			BFormRadio,
			BTable,
			BButtonGroup,
			draggable,
			MediaBrowser,
			SidebarEditor
		}
	}
</script>

<style scoped lang="scss">
	.table th, .table td {
		vertical-align: middle !important;
		text-align: center;
	}
	.flip-list-move {
		transition: transform 0.5s;
	}
	.no-move {
		transition: transform 0s;
	}
	.ghost {
	  	opacity: 0.5;
	  	background: #c8ebfb;
	}
	.b-icon {
		cursor: pointer;
	}
</style>