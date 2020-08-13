<template>
    <div>
        <VueTrix v-model="data" />
    </div>
</template>

<script>
    import VueTrix from "vue-trix";

    export default {
        props: {
            preload: null
        },
        data() {
            return {
                data: null
            }
        },
        created() {
            this.data = this.preload;
        },
        watch: {
            data(newVal, oldVal) {
                if(!oldVal) return;
                this.$emit('updated', newVal);
            }
        },
        components: {
            VueTrix
        }
    }
</script>
