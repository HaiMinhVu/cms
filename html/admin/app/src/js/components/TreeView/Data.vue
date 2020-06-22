<template>
    <div class="tree">
        <ul class="tree-list">
            <ItemTree :item="data"></ItemTree>
        </ul>
    </div>
</template>

<script>
    import ItemTree from "./ItemTree.vue";

    export default {
        props: {
            data: Object
        },
        components: {
            ItemTree
        }
    }
</script>

<style lang="scss">
    .tree-list ul {
        padding-left: 6px;
        margin: 0 0 10px 10px;
        list-style: none;
        border-left: 5px solid rgba(0,0,0,0.2);
    }
</style>
