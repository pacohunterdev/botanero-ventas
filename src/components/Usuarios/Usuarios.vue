<template>
    <section>
        <br>
        <p class="title is-1 has-text-weight-bold">
            <b-icon
                icon="account-group"
                size="is-large"
                type="is-primary"
                tag="router-link"
                to="/registrar-usuario">
            </b-icon>
            Usuarios

            <b-button 
            type="is-success" 
            size="is-large"
            icon-left="account-multiple-plus"
            class="is-pulled-right"
            tag="router-link"
            to="/registrar-usuario">
            Añadir usuario
            </b-button>
        </p>

        <b-select v-model="perPage" >
            <option value="5">5 por página</option>
            <option value="10">10 por página</option>
            <option value="15">15 por página</option>
            <option value="20">20 por página</option>
        </b-select>

        <b-table
            :data="usuarios"
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

            <b-table-column field="correo" label="Correo" sortable v-slot="props">
                {{ props.row.correo }}
            </b-table-column>

            <b-table-column field="nombre" label="Nombre" searchable sortable v-slot="props">
                {{ props.row.nombre }}
            </b-table-column>

            <b-table-column field="telefono" label="Teléfono" searchable sortable v-slot="props">
                {{ props.row.telefono }}
            </b-table-column>

            <b-table-column field="acciones" label="Acciones"  v-slot="props">
                <div class="field is-grouped">
                    <p class="control">
                        <b-button 
                        type="is-danger"
                        icon-left="delete"
                        @click="eliminar(props.row)">
                            Eliminar
                        </b-button>
                    </p>
                    <p class="control">
                        <b-button 
                        type="is-info"
                        icon-left="pen"
                        @click="editar(props.row.id)">
                            Editar
                        </b-button>
                    </p>
                </div>    
            </b-table-column>
        </b-table>
        <b-loading :is-full-page="true" v-model="cargando" :can-cancel="false"></b-loading>

    </section>
</template>
<script>
import HttpService from '../../Servicios/HttpService'
export default {
    name: "Usuarios",

    data: ()=> ({
        usuarios: [],
        cargando: false,
        isPaginated: true,
        isPaginationSimple: false,
        isPaginationRounded: true,
        paginationPosition: 'bottom',
        defaultSortDirection: 'asc',
        sortIcon: 'arrow-up',
        sortIconSize: 'is-small',
        currentPage: 1,
        perPage: 10,
    }),

    mounted(){
        this.obtenerUsuarios()
    },

    methods: {
        eliminar(usuario){
            this.$buefy.dialog.confirm({
                title: 'Eliminar el usuario ' + usuario.nombre,
                message: '¿Seguro que deseas eliminar el usuario? Esta acción no se puede deshacer',
                confirmText: 'Sí, eliminar',
                cancelText: 'No, salir',
                type: 'is-danger',
                hasIcon: true,
                onConfirm: () => {
                    HttpService.eliminar("eliminar_usuario.php", usuario.id)
                    .then(eliminado => {
                        if(eliminado) {
                            this.obtenerUsuarios()
                            this.$buefy.toast.open('Usuario eliminado')
                        }
                    })
                        
                }
            })
        },

        editar(idUsuario) {
            this.$router.push({
                name: "EditarUsuario",
                params: { id: idUsuario },
            })
        },

        obtenerUsuarios(){
            this.cargando = true
            HttpService.obtener("obtener_usuarios.php")
            .then(resultado=> {
                this.usuarios = resultado
                this.cargando = false
            })
        }   
    }
}
</script>