<template>
    <section>
        <TreeData :data="item" v-for="item in items" :key="item.id" />
    </section>
</template>

<script>
    import {
        BListGroup,
        BListGroupItem
    } from 'bootstrap-vue';
    import TreeData from './Data.vue';
    import httpClient from '../../httpClient';

    export default {
        props: {
            items: {
                type: Array,
                default: []
            },
            isChild: {
                type: Boolean,
                default: false
            }
        },
        components: {
            BListGroup,
            BListGroupItem,
            TreeData
        }
    }
</script>

<style scoped>
    ul.tree-list {
        list-style: none;
        margin-bottom: 0;
        padding: 5px 0;
    }
</style>
