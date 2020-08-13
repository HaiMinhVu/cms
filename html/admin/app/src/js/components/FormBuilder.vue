<template>
	<section>
		<BFormGroup class="mb-2" :label="selectFormLabel">
			<BFormSelect v-model="selectedForm" :options="selectOptions"></BFormSelect>
		</BFormGroup>
		<BTabs>
			<BTab title="Submissions">
				<Submissions :form-client="formClient" :form-slug="selectedForm" v-if="hasSelectedForm" />
			</BTab>
			<BTab title="Editor (Coming Soon)" disabled></BTab>
		</BTabs>
	</section>
</template>

<script>
import {
	BButton,
	BIconBoxArrowRight,
	BFormSelect,
	BFormGroup,
	BTab,
	BTabs
} from 'bootstrap-vue';
import httpClient from '../httpClient';
import { uniq } from 'lodash';
import { formatTitle } from '../filters';
import Submissions from './FormBuilder/Submissions.vue';

export default {
	data() {
		return {
			formClient: null,
			rawData: [],
			selectedForm: null,
			selectedFormData: null
		}
	},
	created() {
		this.init();
	},
	methods: {
		init() {
			this.formClient = new httpClient(CONFIG.services.slmk.form_api);
			this.getForms();
		},
		async getForms() {
			const { data } = await this.formClient.get();
			this.rawData = data;
		},
		async getSelectedForm() {
			const { data } = await this.formClient.get(this.selectedForm);
			this.selectedFormData  = data.data;
		}
	},
	computed: {
		tags() {
			const tags = this.rawData.map(item => item.name);
			return uniq(tags);
		},
		selectOptions() {
			const tags = uniq(this.rawData.map(item => item.name));
			return tags.map(tag => {
				return {
					value: tag,
					text: formatTitle(tag, '-')
				}
			});
		},
		hasSelectedForm() {
			return !!this.selectedForm;
		},
		selectFormLabel() {
			return (this.hasSelectedForm) ? '' : 'Select Form';
		}
	},
	watch: {
		selectedForm() {
			this.getSelectedForm();
		}
	},
	components: {
		BButton,
		BIconBoxArrowRight,
		BFormSelect,
		BFormGroup,
		BTab,
		BTabs,
		Submissions
	},
	filters: {
		formatTitle: str => formatTitle(str, '-')
	}
}
</script>
