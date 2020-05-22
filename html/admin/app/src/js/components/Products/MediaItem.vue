<template>
	<BMedia class="align-items-center">
		<template v-slot:aside>
			<BImgLazy blank-width="42" blank-height="42" thumbnail :src="product.image" />
		</template>					
		<BMediaBody>
			<p class="m-0">{{ product.name }}</p>
			<p class="m-0"><small>{{ product.sku }}</small></p>
		</BMediaBody>
	</BMedia>
</template>

<script>
	import {
		BImgLazy,
		BMedia,
		BMediaBody
	} from 'bootstrap-vue';

	export default {
		props: {
			product: Object
		},
		components: {
			BImgLazy,
			BMedia,
			BMediaBody
		}
	}
</script>