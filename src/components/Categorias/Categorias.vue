<template>
    <div>
        <br>
        <p class="title is-1 has-text-weight-bold">
            <b-icon
                icon="archive-outline"
                size="is-large"
                type="is-primary">
            </b-icon>
            Categorías 
            <b-button type="is-success" 
            size="is-large"
            icon-left="plus"
            class="is-pulled-right"
            @click="abrirModal('registra')">
            Añadir categoría
            </b-button>
        </p>
        
        <b-select v-model="perPage" >
            <option value="5">5 por página</option>
            <option value="10">10 por página</option>
            <option value="15">15 por página</option>
            <option value="20">20 por página</option>
        </b-select>
        <b-table
            :data="categorias"
            :paginated="isPaginated"
            :per-page="perPage"
            :bordered="true"
            :current-page.sync="currentPage"
            :pagination-simple="isPaginationSimple"
            :pagination-position="paginationPosition"
            :default-sort-direction="defaultSortDirection"
            :pagination-rounded="isPaginationRounded"
            :sort-icon="sortIcon"
            :sort-icon-size="sortIconSize"
            aria-next-label="Next page"
            aria-previous-label="Previous page"
            aria-page-label="Page"
            aria-current-label="Current page">


            <b-table-column field="icono" label=""  v-slot="props">
                <b-icon
                icon="noodles"
                size="is-small"
                v-if="props.row.tipo === 'PLATILLO'">
                </b-icon>

                <b-icon
                icon="cup"
                size="is-small"
                v-if="props.row.tipo === 'BEBIDA'">
                </b-icon>
            </b-table-column>

            <b-table-column field="tipo" label="Tipo" sortable v-slot="props">
                {{ props.row.tipo }}
            </b-table-column>

            <b-table-column field="nombre" label="Nombre" sortable v-slot="props">
                {{ props.row.nombre }}
            </b-table-column>

            <b-table-column field="descripcion" label="Descripción" sortable v-slot="props">
                {{ props.row.descripcion }}
            </b-table-column>

            <b-table-column field="acciones" label="Acciones"  v-slot="props">
                <b-button 
                type="is-danger"
                icon-left="delete"
                @click="eliminar(props.row)">
                    Eliminar
                </b-button>

                <b-button 
                type="is-info"
                icon-left="pen"
                @click="editar(props.row)">
                    Editar
                </b-button>
            </b-table-column>



        </b-table>
        <b-loading :is-full-page="true" v-model="cargando" :can-cancel="false"></b-loading>

         <b-modal
           :active.sync="mostrarModalCategoria"
            has-modal-card
            trap-focus
            :destroy-on-hide="false"
            aria-role="dialog"
            aria-label="Agregar categoría"
            close-button-aria-label="Close"
            aria-modal>
            
            <modal-categoria @registrado="onRegistrado" :categoria="categoria" :titulo="titulo" :tipo="tipo"></modal-categoria>
            
        </b-modal>
        
    </div>
</template>
<script>
import HttpService from '../../Servicios/HttpService'
import ModalCategoria from './ModalCategoria.vue'
export default {
    name: 'Categorias',
    components: {
            ModalCategoria
        },
        data() {
            return {
                mostrarModalCategoria: false,
                estaRegistrando: false,
                estaEditando: false,
                titulo : "",
                tipo: "",
                categoria: {
                    tipo: "",
                    nombre: "",
                    descripcion: ""
                },
                categorias: [],
                isPaginated: true,
                isPaginationSimple: false,
                isPaginationRounded: true,
                paginationPosition: 'bottom',
                defaultSortDirection: 'asc',
                sortIcon: 'arrow-up',
                sortIconSize: 'is-small',
                currentPage: 1,
                perPage: 10,
             
            }
        },

        created() {
            this.obtenerCategorias()
        },

        methods: {
            eliminar(categoria) {
                this.$buefy.dialog.confirm({
                    title: 'Eliminar categoría ' + categoria.nombre,
                    message: '¿Seguro que deseas eliminar la categoría? Esta acción no se puede deshacer',
                    confirmText: 'Sí, eliminar',
                    cancelText: 'No, salir',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => {
                        this.cargando = true
                        HttpService.eliminar("eliminar_categoria.php", categoria.id)
                        .then(eliminado => {
                            if(eliminado) {
                                this.obtenerCategorias()
                                this.$buefy.toast.open('Categoría eliminada')
                                this.cargando = false
                            }
                        })
                        
                    }
                })
            },

            editar(categoria){
                this.abrirModal("edita", categoria)
            },

            abrirModal(tipo, categoria = {}) {
                this.categoria = categoria

                this.mostrarModalCategoria = true
                if(tipo === "registra") {
                    this.tipo = tipo
                    this.titulo = "Agregar "
                }
                if(tipo === "edita") {
                    this.tipo = tipo
                    this.titulo = "Editar "
                }
            },

            onRegistrado(){
                this.obtenerCategorias()
                this.categoria = {
                    tipo: "",
                    nombre: "",
                    descripcion: ""
                }
            },

            obtenerCategorias() {
                this.cargando = true
                HttpService.obtener("obtener_categorias.php")
                .then(resultado =>{
                    this.categorias = resultado
                    this.cargando = false
                })
            }
        }
}
</script>