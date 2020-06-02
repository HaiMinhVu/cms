<template>
	<section class="container">
		<BTabs>
			<BTab title="Upload">
				<MediaUpload :types="types" :brand-list="brandList" :httpClient="httpClient" @changeTab="changeTab" :force-type="forceType" />
			</BTab>
			<BTab title="Media Library" active>
				<MediaLibrary :types="types" :brand-list="brandList" :httpClient="httpClient" :edit-mode="editMode" :per-page="perPage | parseInt" :force-type="forceType" @selectedAction="selectedMediaItems" />
			</BTab>
		</BTabs>
	</section>
</template>

<script>
	import {
		BTabs,
		BTab
	} from 'bootstrap-vue';
	import { isEmpty, replace, startCase } from 'lodash';
	import httpClient from '../httpClient';
	import MediaLibrary from './Media/Library.vue';
	import MediaUpload from './Media/Upload.vue';

	export default {
		props: {
			editMode: {
				type: Boolean,
				default: true
			},
			perPage: {
				default: 48
			},
			forceType: {
				type: String,
				default: null
			}
		},
		data() {
			return {
				brands: [],
				httpClient,
				selected: null,
				tabs: ['upload', 'media_library'],
				items: [],
				types: {
					image: 'Image',
					manual: 'Manual',
					spec_sheet: 'Spec Sheet',
					catalog: 'Catalog'
				}
			}
		},
		created() {
			this.$set(this, 'selected', 'media_library');	
			this.init();
		},
		methods: {
			async init() {
				this.httpClient = new httpClient;
				this.getBrands();
			},
			async getBrands() {
				const { data } = await this.httpClient.get('brand');
				this.$set(this, 'brands', data.data);
			},
			isActive(type) {
				return this.selected == type;
			},
			setTab(tabName) {
				this.$set(this, 'selected', tabName);
			},
			getTitle(item) {
				return isEmpty(item.display_name) ? item.file_name : item.display_name;
			},
			changeTab(tab) {
				this.selected = tab;
			},
			selectedMediaItems(items) {
				this.$emit('selected', items);
			}
		},
		computed: {
			brandList() {
				return this.brands.map(brand => brand.name);
			}
		},
		filters: {
			formatTitle(str) {
				return startCase(replace(str, '_', ' '));
			},
			parseInt
		},
		components: {
			BTab,
			BTabs,
			MediaLibrary,
			MediaUpload
		}
	}
</script>