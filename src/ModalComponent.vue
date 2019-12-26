<!--
* @package    javimanga/createcrud
* @author     Javier Manga <javimanga93@gmail.com>
* @copyright  2019-2019 The FreakSystem Group
* @license    https://packagist.org/packages/javimanga/createcrud MIT
* @link       https://packagist.org/packages/javimanga/createcrud
* @link       https://github.com/Ki11Z0n3/CreateCRUD
-->
<template>
    <div class="">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear / Editar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hiddeModal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formModal" onsubmit="return false">
                    <div class="form-group" v-for="(value, index) in formModel">
                        <template v-if="value.type == 'select'">
                            <label>{{value.label}}</label>
                            <select :name="value.field" class="form-control">
                                <option v-if="!data[value.field] || data[value.field] == ''" selected disabled>Seleccione</option>
                                <option :selected="data[value.field] == item.value" :value="item.value" v-for="(item, indexItem) in value.items">{{item.label}}</option>
                            </select>
<!--                            <v-select :options="value.items" :value="data[value.field]" @input="setSelected(data[value.field])"/>-->
<!--                            <input type="hidden" :name="value.field" :value="data[value.field]" />-->
                        </template>
                        <template v-else>
                            <label>{{value.label}}</label>
                            <input :type="value.type" class="form-control" :name="value.field" :value="data[value.field]">
                        </template>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="hiddeModal">Cerrar</button>
                <button type="button" class="btn btn-primary" @click="save">Guardar</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ["model", "formModel"],
        data() {
            return {
                data: ''
            };
        },
        created() {
            this.data = this["model"];
        },
        mounted() {
        },
        methods: {
            save(){
                var o = {};
                var a = $('#formModal').serializeArray();
                $.each(a, function() {
                    if (o[this.name]) {
                        if (!o[this.name].push) {
                            o[this.name] = [o[this.name]];
                        }
                        o[this.name].push(this.value || '');
                    } else {
                        o[this.name] = this.value || '';
                    }
                });
                if(this.data.id){
                    o.id = this.data.id;
                }
                this.$emit("saveChanges", o);
            },
            hiddeModal(){
                this.$emit("showHiddeModal");
            }
        }
    }
</script>

<style>
</style>
