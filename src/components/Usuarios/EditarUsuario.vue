<template>
    <section>
        <p class="title is-1 has-text-weight-bold">Editar usuario</p>
        <b-breadcrumb align="is-left" >
            <b-breadcrumb-item tag='router-link' to="/">Inicio</b-breadcrumb-item>
            <b-breadcrumb-item tag='router-link' to="/usuarios">Usuarios</b-breadcrumb-item>
        </b-breadcrumb>
        <datos-usuario @registrado="onRegistrado" :usuario="usuario"></datos-usuario>        
        <b-loading :is-full-page="true" v-model="cargando" :can-cancel="false"></b-loading>

    </section>
</template>
<script>
import HttpService from '../../Servicios/HttpService'
import DatosUsuario from './DatosUsuario.vue'

export default ({
    name: "EditarUsuario",
    components: { DatosUsuario },

    data: () => ({
        usuario: {},
        cargando: false
    }),

    mounted() {
        this.cargando = true
        HttpService.obtenerConDatos(this.$route.params.id, "obtener_usuario_id.php")
        .then(resultado => {
            this.usuario = resultado
            this.cargando = false
        })
    },

    methods: {
        onRegistrado(usuario){
            this.usuario = usuario
            this.cargando = true
            HttpService.registrar(this.usuario, "editar_usuario.php")
            .then(editado => {
                if(editado){
                    this.$buefy.toast.open({
                        message: 'Información actualizada',
                        type: 'is-success'
                    })
                    this.cargando = false
                    this.$router.push({
                        name: "Usuarios",
                    })
                }
            })
        }
    }
})
</script>
