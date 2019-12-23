<template>
    <div class="">
        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead>
            <tr>
                <template v-for="(field, index) in data.tableBuilder">
                    <th v-if="field.field != 'actions'"
                        :id="field.belongToField ? field.field + '_' + field.belongToField : field.hasManyField ? field.field + '_' + field.hasManyField : field.field"
                    >
                                <span v-if="index != data.tableBuilder.length - 1 && field.filter">
                                    <template v-if="field.filter_type == 'select'">
                                        <select-filter-component :field="field" @change="searchField"
                                                                 @changeRemove="removeSearchField"
                                                                 @changeRemoveAll="removeSearchFieldAll"></select-filter-component>
                                    </template>
                                    <template v-if="field.filter_type == 'text'">
                                        <input class="search" type="text" :placeholder="field.label"
                                               :id="'search-'+field.field + (field.hasManyField ? '*' + field.hasManyField : field.belongToField ? '*' + field.belongToField : '')"
                                               @keyup="searchField()"/>
                                    </template>
                                    <i v-if="field.order"
                                       :id="field.belongToField ? 'order_' + field.order + '_' + field.belongToField : field.hasManyField ? 'order_' + field.order + '_' + field.hasManyField : 'order_' + field.order"
                                       @click="orderColumn(field.belongToField ? 'order_' + field.order + '_' + field.belongToField : field.hasManyField ? 'order_' + field.order + '_' + field.hasManyField : 'order_' + field.order)"
                                       class="fas fa-sort-up pointer order"></i>
                                </span>
                        <span v-if="index != data.tableBuilder.length - 1 && !field.filter">{{field.label}} <i
                                v-if="field.order"
                                :id="field.belongToField ? 'order_' + field.order + '_' + field.belongToField : field.hasManyField ? 'order_' + field.order + '_' + field.hasManyField : 'order_' + field.order"
                                @click="orderColumn(field.belongToField ? 'order_' + field.order + '_' + field.belongToField : field.hasManyField ? 'order_' + field.order + '_' + field.hasManyField : 'order_' + field.order)"
                                class="fas fa-sort-up pointer order"></i></span>
                    </th>
                    <th v-if="field.field == 'actions' && Object.getOwnPropertyNames(field.items).length > 1">
                        <center v-if="index == data.tableBuilder.length - 1 && !field.filter">
                            <button class="btn btn-primary">Nuevo</button>
                        </center>
                    </th>
                </template>
            </tr>
            </thead>
            <tbody>
            <tr v-for="row in data.data.data">
                <template v-for="field in data.tableBuilder">
                    <td style="vertical-align:middle;"
                        :style="{color:`${field.color ? field.color : ''}`, backgroundColor:`${field.backgroundColor ? field.backgroundColor : ''}`}"
                        v-if="field.type != 'actions'">
                            <span v-if="field.type == 'belongTo'">
                                <span v-if="row[field.field]">{{row[field.field][field.belongToField]}}</span>
                                <span v-else> - - - </span>
                            </span>
                        <span v-if="field.type == 'hasMany'">
                                <span v-if="row[field.field].length != 0">
                                    <span v-for="hasMany in row[field.field]">
                                        <p style="margin-bottom: 0;">{{hasMany[field.hasManyField]}}</p>
                                    </span>
                                </span>
                                <span v-else> - - - </span>
                            </span>
                        <span v-if="field.type == 'boolean'">
                                <span v-if="row[field.field]">SI</span>
                                <span v-else>NO</span>
                            </span>
                        <span v-if="field.type == 'url'">
                            <span v-if="field.urlLabel"><a :style="{color:`${field.color ? field.color : ''}`}" target="_blank" :href="'http://' + row[field.field]">{{field.urlLabel}}</a></span>
                            <span v-else><a :style="{color:`${field.color ? field.color : ''}`}" target="_blank" :href="'http://' + row[field.field]">{{row[field.field]}}</a></span>
                        </span>
                        <span v-if="field.type == null">{{row[field.field]}}</span>
                    </td>
                    <td style="vertical-align:middle;"
                        v-if="field.type == 'actions' && Object.getOwnPropertyNames(field.items).length > 1">
                                <span>
                                    <center>
                                        <span v-for="item in field.items">
                                            <span data-toggle="tooltip" data-placement="top" title="Ver"
                                                  v-if="item.type == 'show'"
                                                  @click="viewData(row)"><i
                                                    class="fas fa-eye mr-2 pointer text-success"></i></span>
                                            <span data-toggle="tooltip" data-placement="top" title="Editar"
                                                  v-if="item.type == 'edit'"
                                                  @click="editData(row)"><i
                                                    class="fas fa-edit mr-2 pointer text-warning"></i></span>
                                            <span data-toggle="tooltip" data-placement="top" title="Eliminar"
                                                  v-if="item.type == 'delete'"
                                                  @click="deleteData(row)"><i
                                                    class="fas fa-trash-alt pointer text-danger"></i></span>
                                        </span>
                                    </center>
                                </span>
                    </td>
                </template>
            </tr>
            <tr v-if="data.data.data.length == 0">
                <td :colspan="data.tableBuilder.length" align="center">No hay datos que mostrar</td>
            </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="col">
                <p>Mostrar
                    <v-select style="display: inline-block;" :options="listItems" v-model="listSelected"
                              @input="searchField"/>
                    de {{data.data.total}} registros
                </p>
            </div>
            <div class="col text-right">
                <button class="page-link btn btn-link d-inline" :class="data.data.prev_page_url ? '' : 'disabled'"
                        @click="paginate('old')">Anterior
                </button>
                <button class="page-link btn btn-link d-inline" :class="data.data.next_page_url ? '' : 'disabled'"
                        @click="paginate('next')">Siguiente
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import {catchErrors, handleErrors, successHandler, clean} from '../helpers';

    export default {
        props: ["model", "options"],
        data() {
            return {
                data: {},
                buttonOrder: 'fas fa-sort-up',
                page: 1,
                order: null,
                column: null,
                listItems: [
                    {value: '5', label: '5'},
                    {value: '10', label: '10'},
                    {value: '15', label: '15'},
                    {value: '*', label: 'Todos'},
                ],
                searchValues: [],
                listSelected: {value: '10', label: '10'},
            };
        },
        created() {
            this.data = JSON.parse(this["model"]);
            if(this.data.filter_column && this.data.filter_type){
                this.order = this.data.filter_type;
                this.column = this.data.filter_column;
                this.searchField();
                console.log('test');
            }
        },
        mounted() {
            $('.search').each(function () {
                this.style.width = this.placeholder.length + "ch";
            })
        },
        methods: {
            removeSearchField(select = null) {
                if (select) {
                    this.searchValues.splice(this.searchValues.indexOf(select), 1);
                }
            },
            removeSearchFieldAll(select = null) {
                if (select) {
                    this.searchValues.splice(this.searchValues.indexOf(select), 1);
                    this.searchField();
                }
            },
            searchField(select = null, date = null) {
                let routeAction;
                const search = this.searchValues;
                let count = 0;
                $('.search').each(function () {
                    if (this.value != '') {
                        count++;
                        if(search.find(value => value.match(new RegExp( $(this).attr('id').split('-')[1] + '=', 'i')) !== null) !== undefined){
                            search.splice(search.indexOf(search.find(value => value.match(new RegExp( $(this).attr('id').split('-')[1] + '=', 'i')) !== null)), 1);
                            search.push($(this).attr('id').split('-')[1] + '=' + this.value);
                        }else{
                            search.push($(this).attr('id').split('-')[1] + '=' + this.value);
                        }
                    }else{
                        if(search.find(value => value.match(new RegExp( $(this).attr('id').split('-')[1] + '=', 'i')) !== null) !== undefined) {
                            search.splice(search.indexOf(search.find(value => value.match(new RegExp($(this).attr('id').split('-')[1] + '=', 'i')) !== null)), 1);
                        }
                    }
                });
                if(count == 0 && this.searchValues.length == 0){
                    this.searchValues = [];
                }else{
                    this.searchValues = search;
                }
                if (select && !select.value) {
                    this.searchValues.push(select);
                }
                if (this.order && this.column) {
                    this.column = this.column.replace('order_', '');
                    routeAction = route(this.data.prefix + '.index').template + '?page=' + this.page + '&paginate=' + this.listSelected.value + '&order=' + this.order + '&column=' + this.column + '&' + this.searchValues.join('&');
                } else {
                    console.log(this.searchValues);
                    routeAction = route(this.data.prefix + '.index').template + '?page=' + this.page + '&paginate=' + this.listSelected.value + '&' + this.searchValues.join('&');
                }
                $(".loader").css("display", '');
                fetch(routeAction, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(stream => catchErrors(stream))
                    .then(data => {
                        this.data.data = data;
                    })
                    .catch(error => handleErrors(error))
            }
            ,
            paginate(page) {
                if (page == 'next') {
                    this.page = this.page + 1;
                    this.searchField();
                } else if (page == 'old') {
                    this.page = this.page - 1;
                    this.searchField();
                } else {
                    this.page = page;
                    this.searchField();
                }
            }
            ,
            orderColumn(id) {
                if ($('#' + id).hasClass('fa-sort-up')) {
                    const order = 'DESC';
                    $('.order').removeClass('fa-sort-down').addClass('fa-sort-up');
                    $('#' + id).removeClass('fa-sort-up').addClass('fa-sort-down');
                    this.order = order;
                    this.column = id;
                    this.searchField();
                } else {
                    const order = 'ASC';
                    $('.order').removeClass('fa-sort-down').addClass('fa-sort-up');
                    $('#' + id).removeClass('fa-sort-down').addClass('fa-sort-up');
                    this.order = order;
                    this.column = id;
                    this.searchField();
                }
            }
            ,
            newData() {
                location.href = route(this.data.prefix + '.create');
            }
            ,
            viewData(row) {
                location.href = route(this.data.prefix + '.show', row.id);
            }
            ,
            editData(row) {
                location.href = route(this.data.prefix + '.edit', row.id);
            }
            ,
            deleteData(row) {
                this.$swal({
                    title: '¿Está usted seguro?',
                    text: "¡No podrás revertir esto!",
                    type: 'warning',
                    customClass: 'mil-modal--confirmation',
                    showCancelButton: true,
                    confirmButtonClass: 'button button--primary',
                    cancelButtonClass: 'button button--secondary',
                    confirmButtonText: 'Sí, borralo',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                        fetch(route(this.data.prefix + '.destroy', row.id), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            }
                        })
                            .then(response => catchErrors(response))
                            .then(data => {
                                this.data.data = data.data;
                                successHandler(data)
                            })
                            .catch(error => handleErrors(error));
                    }
                })
            }
            ,
        }
    }
</script>

<style>
    .pointer {
        cursor: pointer;
    }

    .search {
        border: none;
    }

    .search::placeholder {
        color: #858796;
        font-weight: bold;
    }
</style>
