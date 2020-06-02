<template>
	<section>
		<BFormInput v-model="query" placeholder="Filter by name or sku"></BFormInput>
		<div class="overflow-auto" style="height:50vh">
			<BListGroup v-if="!loading">
				<BListGroupItem button :disabled="isProductSelected(product.sku)" v-for="product in productList" :key="product.id" @click="selectProduct(product)">
					<ProductMediaItem :product="product" />
				</BListGroupItem>
			</BListGroup>
		</div>
	</section>
</template>

<script>
	import 'bootstrap-vue/dist/bootstrap-vue-icons.min.css'
	import { 
		BRow,
		BCol,
		BIcon,
		BFormInput,
		BImgLazy,
		BIconThreeDots,
		BIconCircleFill,
		BListGroup, 
		BListGroupItem,
		BMedia,
		BMediaAside,
		BMediaBody 
	} from 'bootstrap-vue';
	import httpClient from '../../httpClient.js';
	import ProductMediaItem from './MediaItem.vue';

	export default {
		props: {
			selectedProducts: {
				type: Array
			}
		},
		data() {
			return {
				httpClient: null,
				loading: true,
				products: [],
				query: ''
			}
		},
		created() {
			this.$set(this, 'httpClient', new httpClient);
		},
		mounted() {
			this.getData();
		},
		methods: {
			async getData() {
				const { data } = await this.httpClient.get('product/list');
				this.$set(this, 'products', data);
				this.$set(this, 'loading', false); 
			},
			isProductSelected(sku) {
				return this.selectedProducts.includes(sku);
			},
			selectProduct(product) {
				this.$emit('productSelected', product);
			}
		},
		computed: {
			hasProducts() {
				return this.products.length > 0;
			},
			productList() {
				if(this.hasProducts) {
					return this.filteredProducts;
				}
				return [];
			},
			filteredProducts() {
				if(this.query.length > 0) {
					return this.products.filter(product => {
						const productString = [product.name, product.sku].join(' ').toLowerCase();
						return productString.includes(this.query);
					});
				}
				return this.products;
			}
		},
		components: {
			BRow,
			BCol,
			BIcon,
			BFormInput,
			BImgLazy,
			BIconThreeDots,
			BIconCircleFill,
			BListGroup,
			BListGroupItem,
			BMedia,
			BMediaAside,
			BMediaBody,
			ProductMediaItem 
		}
	}
</script>

<style scoped>
	.list-group-item.disabled, 
	.list-group-item:disabled {
		opacity: 0.5;
		background-color: rgba(72,180,97,.2) !important;
	}
</style>