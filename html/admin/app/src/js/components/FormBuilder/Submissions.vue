<template>
	<section class="submission-container" v-if="loaded">
		<div class="d-flex justify-content-between p-4">
			<h3>{{ title }}</h3>
			<BButton variant="primary" @click="showExportModal">
				<BIconBoxArrowRight /> Export Data
			</BButton>
		</div>
		<hr class="mt-0" />
		<div class="d-flex justify-content-between align-items-center mt-0 mb-3">
			<div class="d-flex justify-content-between">
				<div class="mx-1">
					<label for="from-date">From Date:</label>
    				<BFormDatepicker id="from-date" v-model="fromDate"></BFormDatepicker>
				</div>
				<div class="mx-1">
					<label for="to-date">To Date:</label>
    				<BFormDatepicker id="to-date" v-model="toDate"></BFormDatepicker>
				</div>
			</div>
			<div class="mx-1">
				<label for="to-date">Brand:</label>
				<BFormSelect v-model="selectedBrand">
					<BFormSelectOption value="all">All</BFormSelectOption>
					<BFormSelectOption v-for="brand in brands" :value="brand.id" :key="brand.id">{{ brand.name }}</BFormSelectOption>
				</BFormSelect>
			</div>
			<div class="mx-1">
				<BPagination v-model="page" :per-page="perPage" class="mb-0" :total-rows="total" v-if="hasSubmissions"></BPagination>
			</div>
		</div>
		<BTable
			responsive
			class="m"
			ref="selectTable"
			selectable
			selectMode="single"
			:fields="fields"
			:items="items"
			@row-selected="rowSelected"
		/>
		<div class="d-flex justify-content-between align-items-center mt-0 mb-3">
			<div></div>
			<div class="mx-1">
				<BPagination v-model="page" :per-page="perPage" class="mb-0" :total-rows="total" v-if="hasSubmissions"></BPagination>
			</div>
		</div>
		<BSidebar v-model="hasSelectedRow" width="500px">
			<BListGroup>
				<BListGroupItem v-for="(rowItem, key) in selectedRow" :key="key">
					<strong>{{ getFieldByKey(key) }}:</strong><br />{{ rowItem }}
				</BListGroupItem>
			</BListGroup>
		</BSidebar>
		<BModal v-model="showModal" @ok="exportData">
			<BFormGroup label="Filename">
				<BFormInput v-model="fileName" />
				<BFormText>File will be saved as .csv</BFormText>
			</BFormGroup>
		</BModal>
	</section>
</template>

<script>
import {
	BButton,
	BFormGroup,
	BFormInput,
	BFormDatepicker,
	BFormText,
	BFormSelect,
	BFormSelectOption,
	BIconBoxArrowRight,
	BListGroup,
	BListGroupItem,
	BModal,
	BPagination,
	BSidebar,
	BTable
} from 'bootstrap-vue';
import { formatTitle } from '../../filters';
import { isEmpty, isObject, last, truncate, uniq } from 'lodash';
import { ExportToCsv } from 'export-to-csv';

export default {
	props: {
		brands: Array,
		v2Client: Object,
		formId: Number,
		formSlug: String
	},
	data() {
		return {
			httpClient: null,
			fileName: null,
			formIdentifier: null,
			fromDate: null,
			toDate: null,
			loaded: false,
			page: 1,
			perPage: 0,
			showModal: false,
			submissions: [],
			selectedRow: {},
			selectedBrand: 'all',
			total: 0,
			brandOptions: []
		}
	},
	created() {
		const formIdentifier = (!!this.formId) ? this.formId : this.formSlug;
		this.setFormIdentifier(formIdentifier);
	},
	mounted() {
		this.loaded = true;
	},
	methods: {
		async getSubmissionData(all = false) {
			const params = {
				params: {
					'form-slug': this.formIdentifier,
					page: this.page,
					'from-date': this.fromDate,
					'to-date': this.toDate
				}
			};
			if(this.selectedBrand != 'all') {
				params.params.brand = this.selectedBrand;
			}
			if(all) {
				params.params.all = true;
			}
			const { data } = await this.v2Client.get('form/submission', params);
			return data;
		},
		async getSubmissions() {
			const data = await this.getSubmissionData();
			this.submissions = data.data;
			this.perPage = data.per_page;
			this.total = data.total;
		},
		dateChanged() {
			if(this.page == 1) {
				this.getSubmissions();
			} else {
				this.page = 1;
			}
		},
		csvHeaders(data) {
			const dataKeys = Object.keys(data[0]);
			return dataKeys.map(k => formatTitle(k));
		},
		exportCSV(data) {
			const csvExporter = new ExportToCsv({
				filename: this.fileName,
				showLabels: true,
				headers: this.csvHeaders(data)
			});
			csvExporter.generateCsv(data);
		},
		async exportData() {
			const submissions = await this.getSubmissionData(true);
			const mapped = this.mapSubmissions(submissions);
			const data = JSON.parse(JSON.stringify(mapped));
			this.exportCSV(data);
		},
		setFormIdentifier(newVal) {
			this.formIdentifier = newVal;
			this.getSubmissions();
		},
		getFieldByKey(key) {
			const field = this.fields.find(field => field.key == key);
			return (field) ? field.label : '';
		},
		getValue(formfield_submission) {
			try {
				const val = (isObject(formfield_submission.value)) ? formfield_submission.value.name : formfield_submission.selected_option.option.name;
				return truncate(val);
			} catch(e) {
				return null;
			}
		},
		rowSelected(rows, idx) {
			const row = rows[0];
			this.selectedRow = { ...row };
		},
		showExportModal() {
			this.showModal = true;
		},
		sidebarHidden() {
			this.selectedRow = {};
		},
		baseFileName(str) {
			if(str) {
				const fileNameArr = str.split('.');
				if(fileNameArr.length > 1) fileNameArr.pop();
				return fileNameArr.join();
			}
			return null;
		},
		mapSubmissions(submissions) {
			return submissions.map(s => {
				const fsData = {};
				fsData['created_at'] = s.created_at;
				fsData['brand'] = s.brand.name;
				for(let fs of s.field_submissions) {
					fsData[fs.form_field.name] = this.getValue(fs);
				}
				return fsData;
			});
		}
	},
	computed: {
		defaultFileName() {
			return `${this.formSlug}`;
		},
		fields() {
			if(this.hasSubmissions) {
				let fields = last(this.submissions).field_submissions.map(fs => {
					return {
						key: fs.form_field.name,
						label: fs.form_field.description
					};
				});
				return [
					{ key: "created_at", label: "Submitted At" },
					{ key: "brand", label: "Brand" },
					...fields
				];
			}
			return [];
		},
		hasSubmissions() {
			return this.submissions.length > 0;
		},
		items() {
			if(this.hasSubmissions) {
				return this.mapSubmissions(this.submissions);
			}
			return [];
		},
		hasSelectedRow: {
			get() {
				return !isEmpty(this.selectedRow);
			},
			set(newVal) {
				if(!newVal) {
					this.selectedRow = {};
				}
			}
		},
		fieldKeys() {
			return this.fields.map(field => field.key);
		},
		title() {
			if(this.formSlug) {
				return formatTitle(this.formSlug, '-');
			}
			return null;
		}
	},
	watch: {
		formId(newVal) {
			if(this.loaded) {
				this.setFormIdentifier(newVal);
			}
		},
		formSlug(newVal) {
			if(this.loaded) {
				this.setFormIdentifier(newVal);
			}
		},
		fromDate() {
			this.page = 1;
			this.dateChanged();
		},
		toDate() {
			this.page = 1;
			this.dateChanged();
		},
		page() {
			this.getSubmissions();
		},
		selectedBrand() {
			this.page = 1;
			this.getSubmissions();
		},
		showModal(newVal) {
			if(newVal) {
				this.fileName = this.defaultFileName;
			}
		}
	},
	filters: {
		fieldName(key) {
			return this.getFieldByKey(key);
		}
	},
	components: {
		BButton,
		BFormDatepicker,
		BFormGroup,
		BFormInput,
		BFormSelect,
		BFormSelectOption,
		BFormText,
		BIconBoxArrowRight,
		BListGroup,
		BListGroupItem,
		BModal,
		BPagination,
		BSidebar,
		BTable
	}
}
</script>

<style scoped lang="scss">
.b-table-sticky-header {
	max-height: calc(100vh - 270px);
	@media screen and (min-width: 580px) {
		max-height: calc(100vh - 221px);
	}
	@media screen and (min-width: 1025px) {
		max-width: calc(100vw - 292px);
	}
}
.submission-container {
	max-width: calc(100vw - 10px);
	@media screen and (min-width: 580px) {
		max-width: calc(100vw - 262px);
	}
	@media screen and (min-width: 1025px) {
		max-width: calc(100vw - 292px);
	}
}
.b-table-sticky-header > .table.b-table > thead > tr > th {
	top: -1px;
}
</style>
