<template>
    <section>
        <BButton variant="success" @click="toggleSidebar">
            <BIconPlus /> Add New Category
        </BButton>
        <hr />
        <TreeViewContainer :items="items" />
        <BSidebar v-model="showSidebar" right width="600px" @hidden="sidebarHiddenEvent">
            <template v-slot:footer="{ hide }">
                <div class="d-flex bg-dark justify-content-between align-content-center text-light align-items-center px-3 py-2">
                    <div>
                        <BButton size="sm" variant="primary" @click="save">Save</BButton>
                        <BButton size="sm" variant="secondary" @click="hide">Cancel</BButton>
                    </div>
                    <BButton size="sm" variant="danger" @click="toggleDeleteModal">Delete</BButton>
                </div>
            </template>
            <CategoryEdit :category-id="categoryId" ref="editor" :categories="categoryList" :brands="brandList" @updated="getCategoryData" @toggleSidebar="toggleSidebar" />
        </BSidebar>
    </section>
</template>

<script>
    import TreeViewContainer from './TreeView/Container.vue';
    import CategoryEdit from './Category/Edit.vue';
    import httpClient from '../httpClient';
    import { flattenDeep } from 'lodash';
    import { EventBus } from '../EventBus';
    import { BButton, BSidebar, BIconPlus } from 'bootstrap-vue';

    const mapItems = (items) => {
        return items.map(item => {
            return {
                id: item.id,
                label: item.label.length ? item.label : 'Uncategorized',
                children: mapItems(item.sub_categories)
            }
        });
    }

    const mapCategoryList = (categories, withPrefix = '') => {
		const mapped = [];
		for(let category of categories) {
			mapped.push({
				value: category.id,
				text: `${withPrefix}${category.label}`
			});
			if('sub_categories' in category && category.sub_categories.length > 0) {
				mapped.push([mapCategoryList(category.sub_categories, `${withPrefix}${category.label} -> `)]);
			}
		}
		return flattenDeep(mapped);
	}

    export default {
        data() {
            return {
                categoryId: null,
                httpClient: null,
                items: [],
                categoryList: [],
                brandList: [],
                showSidebar: false
            }
        },
        created() {
            this.httpClient = new httpClient;
			this.getCategoryData();
            this.getBrandData();
            EventBus.$on('tree-view-item-edit', categoryId => {
                this.categoryId = categoryId;
                this.showSidebar = true;
            });
        },
        methods: {
            async getCategoryData() {
                const { data } = await this.httpClient.get('category');
                this.items = mapItems(data.data);
                this.categoryList = mapCategoryList(data.data);
            },
            async getBrandData() {
                const { data } = await this.httpClient.get('brand');
                this.brandList = data.data;
            },
            save() {
                this.$refs.editor.save();
            },
            sidebarHiddenEvent() {
                this.categoryId = null;
                EventBus.$emit('clear-category-editor');
            },
            toggleDeleteModal() {
                this.$refs.editor.toggleDeleteModal();
            },
            toggleSidebar() {
                this.showSidebar = !this.showSidebar;
            }
        },
        components: {
            BButton,
            BIconPlus,
            BSidebar,
            CategoryEdit,
            TreeViewContainer
        }
    }
</script>
