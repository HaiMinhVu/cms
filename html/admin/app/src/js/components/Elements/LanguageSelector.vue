<template>
    <section>
        <div class="row align-items-center">
            <div class="col-auto">
                <span class="px-1" v-for="selectedLanguage in selectedLanguages">
                    <BBadge variant="primary">{{ selectedLanguage.value }} <BIconXCircleFill @click="remove(selectedLanguage.id)" /></BBadge>
                </span>
            </div>
            <div class="col-auto">
                <BFormSelect size="sm" v-model="selectedLanguage" @change="add" :options="languageOptions">
                    <template v-slot:first>
                        <BFormSelectOption :value="null" disabled>Select Language</BFormSelectOption>
                    </template>
                </BFormSelect>
            </div>
        </div>
    </section>
</template>

<script>
    import httpClient from '../../httpClient';
    import {
        BBadge,
        BFormSelect,
        BFormSelectOption,
        BIconXCircleFill,
    } from 'bootstrap-vue';

    export default {
        props: {
            assigned: Array
        },
        data() {
            return {
                httpClient: null,
                selectedLanguage: null,
                languages: [],
                selectedLanguages: []
            }
        },
        created() {
            this.selectedLanguages = [ ...this.assigned ];
            this.httpClient = new httpClient;
            this.getData();
        },
        methods: {
            async getData() {
                const { data } = await this.httpClient.get('language');
                this.languages = data.data;
            },
            getLanguageObjectById(id) {
                return this.languages.find(l => l.id == id);
            },
            add(id) {
                const languageObject = this.getLanguageObjectById(id);
                if(languageObject) this.selectedLanguages.push(languageObject);
                this.selectedLanguage = null;
            },
            isSelected(id) {
                return this.selectedIds.includes(id);
            },
            remove(id) {
                this.selectedLanguages = this.selectedLanguages.filter(l => l.id != id);
            }
        },
        computed: {
            languageOptions() {
                return this.languages.map(language => {
                    return {
                        text: language.value,
                        value: language.id,
                        disabled: this.isSelected(language.id)
                    }
                });
            },
            selectedIds() {
                return this.selectedLanguages.map(sl => sl.id);
            }
        },
        watch: {
            selectedIds(newVal) {
                this.$emit('selected', newVal);
            }
        },
        components: {
            BBadge,
            BFormSelect,
            BFormSelectOption,
            BIconXCircleFill
        }
    }
</script>
