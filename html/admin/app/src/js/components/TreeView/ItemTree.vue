<template>
    <li class="item-tree">
        <span class="label">
            <div class="row no-gutters">
                <div class="col-auto" @click="toggleCollapse">
                    <BIcon :icon="avatarIcon" variant="primary" size="sm" v-if="hasChildren"></BIcon>
                </div>
                <div class="col" @click="edit">
                    &nbsp;{{ item.label }}
                </div>
            </div>
        </span>
        <BCollapse v-model="collapsed">
            <ul v-if="item.children && hasChildren">
                <ItemTree v-for="child in item.children" :item="child" :key="child.id"></ItemTree>
            </ul>
        </BCollapse>
    </li>
</template>

<script>
    import {
        BAvatar,
        BCollapse,
        BIcon,
        BIconDashCircle,
        BIconPlusCircle
    } from 'bootstrap-vue';
    import { EventBus } from '../../EventBus';

    export default {
        name: "ItemTree",
        props: {
            item: Object
        },
        data() {
            return {
                collapsed: false
            }
        },
        methods: {
            edit() {
                EventBus.$emit('tree-view-item-edit', this.item.id);
            },
            toggleCollapse() {
                this.collapsed = !this.collapsed;
            }
        },
        computed: {
            hasChildren() {
                return this.item.children.length > 0;
            },
            avatarIcon() {
                return !this.collapsed ? 'plus-circle' : 'dash-circle';
            }
        },
        components: {
            BAvatar,
            BCollapse,
            BIcon,
            BIconDashCircle,
            BIconPlusCircle
        }
    }
</script>

<style lang="scss" scoped>
    .item-tree {
        list-style: none;
    }
    .label {
        display: block;
        cursor: pointer;
        padding: 5px;
        transition: all 150ms;
        transform: scale(1);
        &:hover {
            background-color: #f1f1f1;
            transform: scale(1.005);
        }
    }
</style>
