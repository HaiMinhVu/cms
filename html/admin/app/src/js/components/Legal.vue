<template>
    <div class="container-fluid">
        <div class="mb-5" v-for="(type, idx) in types" :key="idx">
            <BFormGroup
                :id="`editor-${type}-${idx}`"
                :label="type"
            >
                <Editor preload="test" @updated="updated($event, idx)" />
            </BFormGroup>
        </div>
    </div>
</template>

<script>
    import { BFormGroup } from 'bootstrap-vue';
    import Editor from './Elements/WYSIWYG.vue';

    export default {
        data() {
            return {
                types: [
                    'ITAR',
                    'EAR99'
                ],
                data: {}
            }
        },
        methods: {
            getTypeKey(idx) {
                return this.types[idx];
            },
            updated(val, idx) {
                const typeKey = this.getTypeKey(idx);
                if(val.length > 0) {
                    this.setData(typeKey, val)
                } else {
                    delete this.data[typeKey];
                }
            },
            setData(key, value) {
                this.data[key] = value;
            },
            getData(key) {
                return this.data[key];
            }
        },
        components: {
            BFormGroup,
            Editor
        }
    }
</script>
