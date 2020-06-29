<template>
    <section>
        <div class="row align-items-center">
            <div class="col">
                <SearchDropdown
                    :selected-ids="selectedIds"
                    :options="optionList"
                    @searched="searched"
                    @selected="selectedAction"
                    :selected-key="selectedKey"
                    :selected-value="selectedValue"
                    :empty-text="emptyText"
                    :type="fileType"
                    />
            </div>
            <div class="col-auto">
                <BButton variant="primary" @click="addItem">Add</BButton>
            </div>
        </div>
    </section>
</template>

<script>
    import httpClient from '../../httpClient';
    import {
        BButton,
        BFormInput,
        BFormSelect,
        BFormSelectOption,
        BListGroup,
        BListGroupItem
    } from 'bootstrap-vue';
    import SearchDropdown from '../Elements/SearchDropdown.vue';
    import { orderBy } from 'lodash';

    export default {
        props: {
            fileType: {
                type: String,
                default: ''
            },
            selectedIds: Array
        },
        data() {
            return {
                httpClient: null,
                inputField: null,
                options: [],
                searchValue: '',
                selected: {}
            }
        },
        created() {
            this.httpClient = new httpClient;
            this.getData();
        },
        methods: {
            focused() {
                this.$refs.select.$el.focus();
            },
            async getData() {
                const { data } = await this.httpClient.get('file', this.getSearchParams());
                this.options = data.data;
            },
            getSearchParams() {
                const params = {
                    params: {
                        limit: 30
                    }
                };

                if(this.hasType) {
                    params.params.type = this.fileType;
                }
                if(this.hasSearch) {
                    params.params.search = this.searchValue;
                }
                return params;
            },
            getOptionByKey(key) {
                const option = this.options.find(option => option.id == key);
                return option ? option : null;
            },
            async addItem() {
                if(this.hasSelection) {
                    const selectedOption = this.getOptionByKey(this.selected.key);
                    this.$emit('selected', { ...selectedOption });
                    await this.$nextTick();
                    this.selected = {};
                }
            },
            searched(newVal) {
                this.searchValue = newVal;
                this.getData();
            },
            selectedAction(newVal) {
                this.selected = newVal;
            }
        },
        computed: {
            hasType() {
                return !!this.fileType;
            },
            hasSearch() {
                return !!this.searchValue;
            },
            hasSelection() {
                return 'key' in this.selected;
            },
            selectedKey() {
                return this.hasSelection ? this.selected.key : null;
            },
            selectedValue() {
                return this.hasSelection ? this.selected.value : null;
            },
            optionList() {
                const list = this.options.map(option => {
                    return {
                        key: option.id,
                        value: option.file_name
                    }
                });
                return orderBy(list, item => item.value.toLowerCase());
            }
        },
        components: {
            BButton,
            BFormInput,
            BFormSelect,
            BFormSelectOption,
            BListGroup,
            BListGroupItem,
            SearchDropdown
        }
    }
</script>
