<template>
	<section>
		<div v-if="hasTitle">
			<h2 class="d-inline">{{ this.title }}</h2><BBadge class="align-top" size="sm" pill>{{ selectedCount }}</BBadge>
		</div>
        <BListGroup>
			<draggable
					v-model="selectedItems"
					@start="drag = true"
					@end="drag = false"
					v-bind="dragOptions">
	            <BListGroupItem v-for="item in selectedItems" :key="item.id">
	                <div class="row align-items-center">
						<div class="col-auto sortable-handle"><BIconList /></div>
	                    <div class="col">
							<div>{{ item.file_name }}</div>
							<div v-if="isManualType">
								<div class="row align-items-center">
									<div class="col-auto">
										<p><small>Languages:</small></p>
									</div>
									<div class="col-auto">
										<LanguageSelector :assigned="item.languages" @selected="languagesSelected($event, item.id)" />
									</div>
								</div>
							</div>
						</div>
	                    <div class="col-auto" @click="remove(item.id)"><BIconTrash variant="danger" /></div>
	                </div>
	            </BListGroupItem>
			</draggable>

            <BListGroupItem>
                <DropdownSelector :selected-ids="selectedIds" :file-type="fileType" @selected="selected" />
            </BListGroupItem>
        </BListGroup>
		<hr />
		<div class="d-flex">
			<BButton variant="primary" @click="save">Save</BButton>
		</div>
	</section>
</template>

<script>
    import httpClient from '../httpClient';
    import DropdownSelector from './Media/DropdownSelector.vue';
    import {
		BBadge,
		BButton,
		BFormSelect,
        BListGroup,
        BListGroupItem,
		BIconList,
        BIconTrash
    } from 'bootstrap-vue';
	import draggable from 'vuedraggable';
	import LanguageSelector from './Elements/LanguageSelector.vue';
	import { map } from 'lodash';

	export default {
		props: {
			fileType: String,
            productId: String,
			title: {
				type: String,
				default: null
			}
		},
        data() {
            return {
                httpClient: null,
                selectedItems: [],
				selectedLanguages: {}
            }
        },
        created() {
            this.httpClient = new httpClient;
            this.getData();
        },
        methods: {
            async getData() {
                const { data } = await this.httpClient.get(`product/${this.productId}/file`, { params: { limit: 50, type: this.fileType }});
                this.selectedItems = data.data;
            },
            remove(optionId) {
                const filtered = this.selectedItems.filter(item => item.id != optionId);
                this.selectedItems = filtered;
            },
			assignedLanguages(languages) {
				return languages.map(l => l.id);
			},
			async save() {
				await this.saveAction();
				await this.saveLanguages();
				this.$root.$bvToast.toast(`Associated ${this.selectedCount} files`, {
				  title: 'Files Saved',
				  autoHideDelay: 3000,
				  appendToast: true,
				  toaster: 'b-toaster-bottom-left',
				  variant: 'success'
				});
			},
			async saveAction() {
				const { data } = await this.httpClient.post(`product/${this.productId}/file`, {
					type: this.fileType,
					ids: this.selectedIds
				});
			},
			saveLanguages() {
				return Promise.all(map(this.selectedLanguages, (languageIds, fileId) => {
					return this.saveItemLanguages(fileId, languageIds);
				}));
			},
			async saveItemLanguages(fileId, languageIds) {
				console.log(languageIds);
				// return;
				const { data } = await this.httpClient.post(`product/${this.productId}/manual/${fileId}/language`, {
				 	ids: languageIds
				});
				console.log(data);
			},
            selected(option) {
                this.selectedItems.push({ ...option });
            },
			languagesSelected(selected, itemId) {
				this.selectedLanguages[itemId] = selected;
			}
        },
        computed: {
			dragOptions() {
		      return {
		        animation: 200,
		        group: "description",
				handle: ".sortable-handle",
		        disabled: false,
		        ghostClass: "ghost",
		        forceFallback: true
		      }
		    },
			hasTitle() {
				return !!this.title;
			},
            selectedIds() {
                return this.selectedItems.map(s => s.id);
            },
			selectedCount() {
				return this.selectedItems.length;
			},
			isManualType() {
				return this.fileType == 'manual';
			}
        },
        components: {
			BBadge,
			BButton,
			BFormSelect,
            DropdownSelector,
            BListGroup,
            BListGroupItem,
			BIconList,
            BIconTrash,
			draggable,
			LanguageSelector
        }
	}
</script>

<style scoped lang="scss">
    .list-group {
        .list-group-item {
			margin-bottom: 1px;
            &:last-child {
				margin-bottom: 0;
                // border-top: 1px solid rgba(0,0,0,0.1);
            }
        }
    }
	.flip-list-move {
		transition: transform 0.5s;
	}
	.no-move {
		transition: transform 0s;
	}
	.ghost {
	  opacity: 0.5;
	  background: #c8ebfb;
	}
	p {
		margin-bottom: 0;
	}
</style>
