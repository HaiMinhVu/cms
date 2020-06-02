<template>
	<section>
		<ProductMediaSelector media-type="reticle" title="Reticle Selector" :product-id="productId" />
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