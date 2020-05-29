<template>
	<section>
		<BButton @click="openModal" variant="primary"><BIconImages /> Select Images</BButton>
		<BTable :items="selectedItems" :fields="fields">
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
						<BFormRadio name="mainImageRadio" @change="mainImageChecked($event, row.item.id)" />
					</div>
				</div>
			</template>
			<template v-slot:cell(remove)="row">
				<BIconDashCircleFill variant="danger" size="lg" />
			</template>
		</BTable>
		<BModal hide-footer size="xl" title="Media Browser" v-model="modalVisible">
			<MediaBrowser per-page="24" force-type="image" :edit-mode="false" @selected="selected" />
		</BModal>
	</section>
</template>

<script>
	import {
		BButton,
		BModal,
		BImgLazy,
		BIconImages,
		BIconList,
		BIconDashCircleFill,
		BFormRadio,
		BTable
	} from 'bootstrap-vue';
	import MediaBrowser from './MediaBrowser.vue';
	import httpClient from '../httpClient';

	export default {
		props: [ 'productId' ],
		data() {
			return {
				modalVisible: false,
				selectedItemIds: [],
				selectedItems: [],
				fields: ['', 'image', 'name', 'main_image', 'remove'],
				httpClient: null
			}
		},	
		created() {
			this.httpClient = new httpClient;
			this.getInitialData();
		},
		methods: {
			async getInitialData() {
				const { data } = await this.httpClient.get(`product/${this.productId}/file`); 
				this.addToSelected(data.data);
			},
			openModal() {
				this.modalVisible = true;
			},
			selected(items) {
				this.selectedItemIds = items;
				this.modalVisible = false;
			},
			addToSelected(data) {
				this.selectedItems = [...this.selectedItems, ...data];
			},
			async getItems() {
				const { data } = await this.httpClient.get('file', { params: { ids: this.selectedItemIds } });
				this.addToSelected(data);
			},
			mainImageChecked($event, id) {
				console.log([$event, id]);
			}
		},
		computed: {
			hasSelectedItems() {
				return this.selectedItems.length > 0;
			}
		},
		components: {
			BButton,
			BModal,
			BImgLazy,
			BIconImages,
			BIconList,
			BIconDashCircleFill,
			BFormRadio,
			BTable,
			MediaBrowser
		}
	}
</script>

<style>
	.table th, .table td {
		vertical-align: middle !important;
		text-align: center;
	}
</style>