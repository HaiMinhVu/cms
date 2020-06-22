<template>
	<section class="container">
        <h3>{{ actionText }}</h3>
        <form>
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" v-model="name" class="form-control" />
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label>Brand</label>
                        <BFormSelect v-model="brandId" :options="brandOptions" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Parent Category</label>
                <BFormSelect v-model="parentId" :options="categoryOptions"></BFormSelect>
            </div>
            <div class="form-group">
                <label>Text</label>
                <textarea rows="2" v-model="text" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Short Description</label>
                <textarea rows="2" v-model="shortDescription" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Long Description</label>
                <textarea rows="4" v-model="longDescription" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Main Image</label>
                <div>
                    <ImageSelector v-if="!hasThumbnail" />
                    <div class="image-container" v-if="hasThumbnail">
                        <BImg width="150px" height="150px" thumbnail fluid :src="thumbnailUrl" />
                        <BIconXCircleFill @click="removeImage" />
                    </div>
                </div>
            </div>
        </form>
        <BModal v-model="showDeleteModal" hide-footer>
            <template v-slot:modal-title>
                Delete Category?
            </template>
            <div class="d-block text-center">
                <h5>Are you sure you want to delete {{ this.name }}?</h5>
            </div>
            <div class="d-flex justify-content-between">
                <BButton class="mt-3" @click="toggleDeleteModal" variant="secondary">Cancel</BButton>
                <BButton class="mt-3" @click="delCategory" variant="danger">OK</BButton>
            </div>
        </BModal>
	</section>
</template>

<script>
    import {
        BButton,
        BFormSelect,
        BImg,
        BIconXCircleFill,
        BModal
    } from 'bootstrap-vue';
    import ImageSelector from './ImageSelector.vue';
    import { EventBus } from '../../EventBus';
    import httpClient from '../../httpClient';



	export default {
        props: {
            categoryId: {
                type: Number,
                default: 0
            },
            categories: Array,
            brands: Array
        },
        data() {
            return  {
                parentId: null,
                name: null,
                originalName: null,
                text: null,
                shortDescription: null,
                longDescription: null,
                thumbnailId: null,
                brandId: null,
                httpClient: null,
                thumbnailUrl: null,
                updating: false,
                showDeleteModal: false
            }
        },
        created() {
            this.httpClient = new httpClient;
            EventBus.$on('category-thumb-selected', selectedId => {
                this.thumbnailId = selectedId;
            });
            EventBus.$on('clear-category-editor', () => {
                this.resetData();
            });
        },
        methods: {
            goBack() {
                this.$emit('toggleSidebar');
            },
            async getThumbDetails(fileId) {
                const { data } = await this.httpClient.get(`file/${fileId}`);
                this.thumbnailUrl = (data.data) ? data.data.url : null;
            },
            async loadCategory(categoryId) {
                const { data } = await this.httpClient.get(`category/${categoryId}`);
                this.setLoadedData(data.data);
            },
            toggleDeleteModal() {
                this.showDeleteModal = !this.showDeleteModal;
            },
            async delCategory() {
                this.showDeleteModal = false;
                this.updating = true;
                await this.delCategoryAction();
                this.updating = false;
                this.showToast('Deleted Category', 'warning');
                this.$emit('updated');
                this.goBack();
            },
            async delCategoryAction() {
                const { data } = await this.httpClient.delete(`category/${this.categoryId}`);
            },
            setLoadedData(data) {
                this.name = data.name;
                this.originalName = data.name;
                this.parentId = data.parent_id;
                this.text = data.text;
                this.shortDescription = data.short_description;
                this.longDescription = data.long_description;
                this.brandId = data.brand_id;
                this.thumbnailId = data.thumbnail_id;
            },
            removeImage() {
                this.thumbnailId = null;
            },
            resetData() {
                ['name', 'parentId', 'text', 'shortDescription', 'longDescription', 'brandId', 'thumbnailId'].map(key => {
                    this.$set(this, key, null);
                });
            },
            compileData() {
                return {
                    parent_id: this.parentId,
                    name: this.name,
                    text: this.text,
                    short_description: this.shortDescription,
                    long_description: this.longDescription,
                    thumbnail_id: this.thumbnailId,
                    brand_id: this.brandId
                };
            },
            async save() {
                this.updating = true;
                if(this.isEdit) {
                    await this.updateAction();
                } else {
                    await this.saveAction();
                }
                this.showToast();
                this.updating = false;
                this.$emit('updated');
                this.goBack();
            },
            showToast(title = 'Saved Category', variant = 'success') {
                this.$root.$bvToast.toast(this.name, {
                  title: title,
                  autoHideDelay: 3000,
                  appendToast: true,
                  toaster: 'b-toaster-bottom-left',
                  variant: variant
                });
            },
            async saveAction() {
                const { data } = await this.httpClient.post('category', { ...this.compileData() });
            },
            async updateAction() {
                const { data } = await this.httpClient.put(`category/${this.categoryId}`, { ...this.compileData() });
            }
        },
        computed: {
            actionText() {
                return this.isEdit ? `Edit: ${this.originalName}` : 'Add New Category';
            },
            hasThumbnail() {
                return !!this.thumbnailId && !!this.thumbnailUrl;
            },
            brandOptions() {
                return this.brands.filter(brand => !!brand.brand_id).map(brand => {
                    return {
                        text: brand.brand_name,
                        value: brand.brand_id
                    };
                });
            },
            categoryOptions() {
                if(this.isEdit) {
                    return this.categories.map(category => {
                        return {
                            disabled: category.value == this.categoryId,
                            ...category
                        };
                    });
                }
                return this.categories;
            },
            isEdit() {
                return !!this.categoryId;
            }
        },
        watch: {
            categoryId(newVal) {
                if(newVal) {
                    this.loadCategory(newVal);
                }
            },
            thumbnailId(newVal) {
                if(newVal && newVal > 0) {
                    this.getThumbDetails(newVal);
                } else {
                    this.thumbnailUrl = null;
                }
            }
        },
        components: {
            BButton,
            BFormSelect,
            BImg,
            BIconXCircleFill,
            BModal,
            ImageSelector
        }
    }
</script>

<style scoped lang="scss">
    .image-container {
        display: block;
        position: relative;
        .bi-x-circle-fill {
            position: absolute;
            color: rgba(220, 53, 69, 0.6);
            transform: translateX(calc(-100% - 8px)) translateY(8px);
            transition: color 150ms;
            cursor: pointer;
            &:hover {
                color: rgba(220, 53, 69, 1);
            }
        }
    }
</style>
