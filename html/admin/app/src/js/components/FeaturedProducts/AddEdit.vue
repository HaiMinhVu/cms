<template>
	<section class="container">
		<hr />
		<div>
			<BButton @click="goBack">
				<BIconArrowLeftShort></BIconArrowLeftShort>
				Go Back
			</BButton>
		</div>
		<br />
		<div>
			<BFormGroup
			label="Description"
			label-class="font-weight-bold"
			>
				<BFormInput :id="`input_description_${editModel.id}`" v-model="description" />
			</BFormGroup>
		</div>
		<div>
			<div class="row align-items-end">
				<div class="col">
					<label class="font-weight-bold">Assigned Products ({{ productsCount }})</label>
				</div>
				<div class="col mb-2 text-right">
					<BButton @click="toggleProductSelectorModule" variant="success">
						<BIconPlus></BIconPlus> Add Product
					</BButton>
				</div>
			</div>
			<div class="list-group"> 
				<draggable v-model="productList">
					<div class="list-group-item" v-for="(product, idx) in products" :key="idx">
						<div class="row align-items-center">
							<div class="col-1 move-handler">
								<BIconList />
							</div>
							<div class="col">
								<ProductMediaItem :product="product" />
							</div>
							<div class="col-1">
								<BIconTrash variant="danger" @click="removeProduct(product.sku)" />
							</div>
						</div>
					</div>
				</draggable>
				
			</div>
			<br />
			<BButton variant="primary" @click="save" :disabled="submitting">
				<BIconCloudUpload></BIconCloudUpload> Save
			</BButton>
		</div>
		<BModal v-model="showProductSelector" title="Product Selector">
			<ProductSelector :selectedProducts="selectedProducts" @productSelected="productSelected" />
		</BModal>
	</section>
</template>

<script>
	import { 
		BButton,
		BFormGroup,
		BFormInput,
		BIconPlus,
		BIconArrowLeftShort,
		BIconList,
		BIconCloudUpload,
		BIconTrash,
		BListGroup,
		BListGroupItem,
		BModal,
		BToast
	} from 'bootstrap-vue';
	import ProductSelector from '../Products/Selector';
	import ProductMediaItem from '../Products/MediaItem';
	import sortableDirective from '../../directives/sortable.js';
	import draggable from 'vuedraggable';

	export default {
		props: {
			httpClient: {
				type: Object
			},
			editModel: {
				type: Object
			}
		},
		data() {
			return  {
				description: '',
				showProductSelector: false,
				submitting: false,
				products: []
			}
		},
		created() {
			if(this.isEdit) {
				this.loadData();				
			}
		},
		methods: {
			loadData() {
				this.$set(this, 'description', this.editModel.description);
				this.setLoadedProducts();
			},
			goBack() {
				this.$emit('goBack');
			},
			productSelected(data) {
				this.products.push(data);
				this.toggleProductSelectorModule();
			},
			removeProduct(sku) {
				const products = this.products.filter(product => {
					return product.sku != sku;
				});
				this.$set(this, 'products', products);
			},
			async save() {
				this.submitting = true;
				if(this.isEdit) {
					await this.updateAction();
				} else {
					await this.createAction();
				}
				this.submitting = false;
				this.$root.$bvToast.toast(`${this.description} saved`, {
		          title: 'Featured Product List',
		          autoHideDelay: 3000,
		          appendToast: true,
		          toaster: 'b-toaster-bottom-left',
		          variant: 'success'
		        });
		        this.goBack();
			},
			getSaveData() {
				return {
					description: this.description,
					skus: this.products.map(product => product.sku)
				}
			},
			async createAction() {
				await this.httpClient.post('featured-product', this.getSaveData());
			},
			async updateAction() {
				await this.httpClient.put(`featured-product/${this.editModel.id}`, this.getSaveData());
			},
			setLoadedProducts() {
				const products = this.editModel.products;
				this.$set(this, 'products', products);
			},
			toggleProductSelectorModule() {
				this.$set(this, 'showProductSelector', !this.showProductSelector);
			}
		},
		computed: {
			isEdit() {
				return Object.keys(this.editModel).length > 0;
			},
			productsCount() {
				if(this.products) {
					return this.products.length;
				} else {
					return 0;
				}
			},
			selectedProducts() {
				return this.products.map(product => product.sku);
			},
			productList: {
				get() {
					return this.products;
				},
				set(products) {
					this.$set(this, 'products', products);
				}
			}
		},	
		components: {
			BButton,
			BFormGroup,
			BFormInput,
			BIconPlus,
			BIconArrowLeftShort,
			BListGroup,
			BListGroupItem,
			BIconList,
			BIconCloudUpload,
			BIconTrash,
			BModal,
			ProductMediaItem,
			ProductSelector,
			BToast,
			draggable
		},
		directives: {
			sortableDirective
		}
	}
</script>