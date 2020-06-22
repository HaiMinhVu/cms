<template>
	<section>
		<BButton @click="toggleModal">
			<BIconImage size="xs" /> Select Image
		</BButton>
		<BModal hide-footer size="xl" title="Media Browser" v-model="modalVisible">
			<MediaBrowser per-page="24" force-type="image" :edit-mode="false" :single-selector="true" @selected="selected" />
		</BModal>
	</section>
</template>

<script>
	import {
		BButton,
		BIconImage,
		BModal
	} from 'bootstrap-vue';
	import MediaBrowser from '../MediaBrowser.vue';
	import { EventBus } from '../../EventBus.js';

	export default {
		data() {
			return  {
				modalVisible: false,
			}
		},
		methods: {
			selected(selected) {
				EventBus.$emit('category-thumb-selected', selected[0]);
				this.toggleModal();
			},
			toggleModal() {
				this.modalVisible = !this.modalVisible;
			}
		},
		components: {
			BButton,
			BIconImage,
			BModal,
			MediaBrowser
		}
	}
</script>
