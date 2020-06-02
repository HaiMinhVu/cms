<template>
	<section>
		<ProductMediaSelector media-type="image" title="Image Selector" :product-id="productId" />
	</section>
</template>

<script>
	import ProductMediaSelector from './ProductMediaSelector.vue';

	export default {
		props: { 
			productId: String
		},
		components: {
			ProductMediaSelector
		}
	}
</script>