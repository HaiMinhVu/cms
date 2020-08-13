<template>
    <section>
        <div class="d-flex justify-content-between align-middle">
            <BButton variant="success" @click="addNew" :disabled="loading">
                <BIconPlus /> Add New Brand
            </BButton>
            <BFormCheckbox v-model="showDeleted">Show Deleted</BFormCheckbox>
        </div>
        <hr />
        <BTable :items="tableItems" :fields="tableFields">
            <template v-slot:cell(actions)="row">
                <div class="text-right">
                    <BButton size="sm" :variant="(row.item.active) ? 'danger' : 'secondary'" @click="toggleDelete(row.item.id)">
                        <span v-if="row.item.active"><BIconX /> Delete</span>
                        <span v-else><BIconArrowCounterclockwise /> Undelete</span>
                    </BButton>
                </div>
            </template>
        </BTable>
        <BModal title="Add New Brand" centered v-model="modalShow" @ok="saveNewBrand">
            <BFormGroup label="Name">
                <BFormInput v-model="newBrandName" />
            </BFormGroup>
        </BModal>
    </section>
</template>

<script>
    import {
        BButton,
        BFormCheckbox,
        BFormGroup,
        BFormInput,
        BIconArrowCounterclockwise,
        BIconX,
        BIconPencil,
        BIconPlus,
        BModal,
        BTable
    } from 'bootstrap-vue';
    import httpClient from '../httpClient';

    export default {
        data() {
            return {
                brands: [],
                httpClient: null,
                loading: true,
                modalShow: false,
                newBrandName: '',
                showDeleted: false,
                tableFields: ['name', 'slug', 'actions'],
                toggleDeleteText: 'Delete'
            }
        },
        created() {
            this.httpClient = new httpClient;
            this.getData();
        },
        methods: {
            addNew() {
                this.modalShow = true;
            },
            async getData() {
                const { data } = await this.httpClient.get('brand');
                this.brands = data.data;
                this.loading = false;
            },
            async saveNewBrand() {
                const { data } = await this.httpClient.post('brand', {
                    name: this.newBrandName
                });
                this.getData();
            },
            async toggleDelete(prefix = null) {
                console.log(prefix)
                if(prefix) {
                    const { data } = await this.httpClient.post(`brand/toggle/${prefix}`);
                    this.getData();
                }
            }
        },
        computed: {
            filtered() {
                if(this.showDeleted) return this.brands;
                return this.brands.filter(b => b.active == 1);
            },
            tableItems() {
                return this.filtered.map(brand => {
                    return {
                        id: brand.site_list_id,
                        active: brand.active,
                        name: brand.brand_name,
                        slug: brand.brand_slug
                    }
                });
            }
        },
        components: {
            BButton,
            BFormCheckbox,
            BFormGroup,
            BFormInput,
            BIconArrowCounterclockwise,
            BIconX,
            BIconPencil,
            BIconPlus,
            BModal,
            BTable
        }
    }
</script>
