<template>
	<section class="submission-container">
    <BTable
      responsive
      sticky-header
      ref="selectTable"
      selectable
      selectMode="single"
      :fields="fields"
      :items="items"
      @row-selected="rowSelected"
      v-if="hasSubmissions"
    />
    <BListGroup v-if="false">
      <BListGroupItem class="mb-1" v-for="submission in submissions" :key="`submission_${submission.id}`">
        <div class="row">
          <div class="col-md-6 col-lg-4" v-for="field_submission in submission.field_submissions" :key="`field_submission_${field_submission.id}`">
            {{ field_submission.form_field.description }}: <strong>{{ getValue(field_submission) }}</strong>
          </div>
        </div>
      </BListGroupItem>
    </BListGroup>
    <BSidebar v-model="hasSelectedRow">
      <BListGroup>
        <BListGroupItem v-for="(rowItem, key) in selectedRow" :key="key">
          <strong>{{ key }}:</strong> {{ rowItem }}
        </BListGroupItem>
      </BListGroup>
    </BSidebar>
	</section>
</template>

<script>
	import {
    BListGroup,
    BListGroupItem,
    BSidebar,
    BTable
  } from 'bootstrap-vue';
  import { isEmpty, isObject, truncate } from 'lodash';

	export default {
	   props: {
       formClient: Object,
       formId: Number,
       formSlug: String
     },
     data() {
       return {
         formIdentifier: null,
         loaded: false,
         submissions: [],
         selectedRow: {}
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
       async getSubmissions() {
         const { data } = await this.formClient.get('submission', {
           params: {
             'form-slug': this.formIdentifier
           }
         });
         this.submissions = data;
       },
       setFormIdentifier(newVal) {
         this.formIdentifier = newVal;
         this.getSubmissions();
       },
       getValue(formfield_submission) {
         const val = (isObject(formfield_submission.value)) ? formfield_submission.value.name : formfield_submission.selected_option.option.name;
         return truncate(val);
       },
       rowSelected(rows, idx) {
         console.log(this.$refs.selectTable)
         const row = rows[0];
         this.selectedRow = { ...row };
       },
       sidebarHidden() {
         this.selectedRow = {};
       }
     },
     computed: {
      fields() {
        if(this.hasSubmissions) {
          return this.submissions[0].field_submissions.map(fs => {
            return {
              key: fs.form_field.name,
              label: fs.form_field.description
            };
          });
        }
        return [];
      },
      hasSubmissions() {
        return this.submissions.length > 0;
      },
      items() {
        if(this.hasSubmissions) {
          return this.submissions.map(s => {
            const fsData = {};
            for(let fs of s.field_submissions) {
              fsData[fs.form_field.name] = this.getValue(fs);
            }
            return fsData;
          });
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
     },
     components: {
       BListGroup,
       BListGroupItem,
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
