<template>
	<section>
		<transition name="component-fade" mode="out-in">
			<div v-if="!isEditing" key="listView">
				<BTable striped hover sticky-header="55vh" :items="featuredProducts" v-if="hasFeaturedProducts" :fields="fields">
					<template v-slot:cell(actions)="row">
						<div class="text-right">
							<BButton size="sm" variant="info" @click="edit(row.item.id)" class="mr-2">
								<BIconPencil></BIconPencil> Edit
							</BButton>
						</div>
					</template>
				</BTable>
				<hr />
				<div class="text-right">
					<BButton variant="success" @click="addNew">
						<BIconPlus></BIconPlus> New Featured List
					</BButton>
				</div>
			</div>
			<div v-if="isEditing" key="addEdit">
				<AddEdit :editModel="editModel" :httpClient="httpClient" @goBack="goBack" />
			</div>
		</transition>
	</section>
</template>

<script>
	import httpClient from '../httpClient';
	import { BButton, BIconPencil, BIconPlus, BTable } from 'bootstrap-vue';
	import AddEdit from './FeaturedProducts/AddEdit.vue';
	import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

	export default {
		name: 'featured-products-editor',
		data() {
			return {
				featuredProducts: [],
				httpClient: null,
				fields: ['description', 'actions'],
				editModel: {},
				editing: false
			}
		},	
		created() {
			this.httpClient = new httpClient('http://localhost:8888/v1/crud');
		},
		mounted() {
			this.populateDataFromApi();
		},
		methods: {
			addNew() {
				this.$set(this, 'editing', true);
			},
			async populateDataFromApi() {
				const { data } = await this.httpClient.get('featured-product');
				this.$set(this, 'featuredProducts', data);
			},
			async edit(id) {
				const { data } = await this.httpClient.get(`featured-product/${id}`);
				this.$set(this, 'editModel', data.data);
				this.$set(this, 'editing', true);
			},
			goBack() {
				this.$set(this, 'editing', false);
				this.$set(this, 'editModel', {});
				this.populateDataFromApi();
			}
		},
		computed: {
			hasFeaturedProducts() {
				return this.featuredProducts.length > 0;
			},
			isEditing() {
				return this.editing;
			}
		},
		components: {
			AddEdit,
			BButton,
			BIconPencil,
			BIconPlus,
			BTable
		}
	}
</script>

<style scoped>
	.b-table-sticky-header > .table.b-table > thead > tr > th {
	    position: -webkit-sticky;
	    position: sticky;
	    top: 0;
	    z-index: 2;
	}
	.b-table-sticky-header, .table-responsive, [class*="table-responsive-"] {
	    margin-bottom: 1rem;
	}
</style>