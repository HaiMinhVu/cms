<template>
    <section>
        <BFormInput placeholder="Select or type to search" :value="selectedValue" v-model="inputField" @focus="setFocus" @blur="unsetFocus($event)" :debounce="debounce" />
        <BListGroup v-if="focused">
            <BListGroupItem v-for="option in options" :disabled="isDisabled(option.key)" class="text-dark" :key="option.key" @click="select($event, option)">{{ option.value }}</BListGroupItem>
        </BListGroup>
    </section>
</template>

<script>
    import {
        BFormInput,
        BListGroup,
        BListGroupItem,
        BFormSelect
    } from 'bootstrap-vue';

    export default {
        props: {
            debounce: {
                type: Number,
                default: 500
            },
            options: {
                type: Array,
                default: []
            },
            selectedKey: {
                type: Number
            },
            selectedValue: String,
            selectedIds: Array
        },
        data() {
            return {
                focused: false,
                // selectedValue: ''
                inputField: ''
            }
        },
        methods: {
            select(e, option) {
                if(this.isDisabled(option.key)) {
                    e.preventDefault();
                } else {
                    this.$emit('selected', option);
                }
            },
            setFocus() {
                this.focused = true;
            },
            updated(newVal) {
                console.log(newVal);
            },
            unsetFocus() {
                setTimeout(() => {
                    this.focused = false;
                }, 100)
            },
            isDisabled(itemId) {
                return this.selectedIds.includes(itemId);
            }
        },
        computed: {
            selected() {
                const selected = this.options.find(o => o.key == this.selectedKey);
                return (selected) ? selected.value : null;
            }
        },
        watch: {
            inputField(newVal) {
                this.$emit('searched', newVal);
            },
            selectedValue(newVal) {
                this.inputField = newVal;
            }
        },
        components: {
            BFormInput,
            BListGroup,
            BListGroupItem,
            BFormSelect
        }
    }
</script>

<style scoped lang="scss">
    section {
        position: relative;
    }
    input.form-control {
        background: #fff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") no-repeat right 0.75rem center/8px 10px
    }
    .list-group {
        position: absolute;
        z-index: 9999;
        max-height: 500px;
        overflow-y: scroll;
        border: 1px solid rgba(0, 0, 0, 0.125);
        width: 100%;
    }
    .list-group-item {
        border: none;
        cursor: pointer;
        transition: background-color 150ms;
        &:hover {
            background-color: #f1f1f1;
        }
        &.disabled {
            background-color: #f1f1f1;
            pointer-events: none;
        }
    }
</style>
