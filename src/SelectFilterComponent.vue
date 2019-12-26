<template>
    <div class="select_filter" @click="toggle">
        <span class="select-filter__placeholder capitalize">
            {{selected || field.label}}
            <span :class="[is_open ? 'icon-ic_drop_up' : 'icon-ic_drop_down']"></span>
        </span>
        <div  v-show="is_open" class="select_filter_options">
            <div @click="select(option)" v-for="option in field.filter_items" class="select_filter_options_item">
                <span style="padding: 20px;">{{option.label}}</span>
            </div>
            <div @click="removeFilter()" class="select_filter_options_item">
                <span style="padding: 20px;">Todas</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['field', 'options',],
        data() {
            return {
                selected: '',
                selectedValue: '',
                is_open: false,
            }
        },
        created() {
            window.addEventListener('click', (e) => {
                if (!this.$el.contains(e.target)){
                    this.is_open = false
                }
            })
        },
        methods: {
            select(option){
                this.selected = option.label;
                this.selectedValue = option.value;
                this.$emit('change', this.field.field + '=' + option.value);
            },
            removeFilter(){
                this.selected = '';
                this.$emit('changeRemoveAll', this.field.field + '=' + this.selectedValue);
                this.selectedValue = '';
            },
            toggle() {
                this.is_open = !this.is_open;
            },
        },
    }
</script>

<style>
    .select_filter {
        cursor: pointer;
        display: inline;
    }
    .select_filter_options {
        position: absolute;
        background-color: white;
        border: 1px solid #e3e6f0;
        z-index: 99;
        height: fit-content;
        max-height: 50%;
        overflow: auto;
    }
    .select_filter_options_item:hover {
        background-color: #e3e6f0;
        cursor: pointer;
    }
</style>

