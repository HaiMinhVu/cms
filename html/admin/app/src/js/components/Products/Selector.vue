<template>
	<section>
		<BFormInput v-model="query" placeholder="Filter by name or sku"></BFormInput>
		<div class="overflow-auto" style="height:50vh">
			<BListGroup v-if="!loading">
				<BListGroupItem button v-for="product in productList" :key="product.id" @click="selectProduct(product)">
					<ProductMediaItem :product="product" />
				</BListGroupItem>
			</BListGroup>
			<div>
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
	import ProductMediaItem from './MediaItem';

	export default {
		data() {
			return {
				httpClient: null,
				loading: true,
				products: [],
				query: ''
			}
		},
		created() {
			this.$set(this, 'httpClient', new httpClient('http://localhost:8888/v1/crud'));
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