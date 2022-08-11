<template>
      <b-navbar class="fondo is-primary">
        <template #brand>
            <b-navbar-item >
                <img :src="logo" alt="logo">
            <span class="has-text-weight-bold">{{ datosLocal.nombre }}</span>
               
            </b-navbar-item>
        </template>
        <template #start>

            <b-navbar-item tag="router-link" :to="{ path: '/' }">
                <b-icon icon="home"></b-icon>
                <span></span>
                Inicio
            </b-navbar-item>

            <b-navbar-item tag="router-link" :to="{ path: '/insumos' }">
                <b-icon icon="food-fork-drink"></b-icon>
                <span></span>
                Insumos
            </b-navbar-item>

            <b-navbar-item tag="router-link" :to="{ path: '/realizar-orden' }">
                <b-icon icon="order-bool-ascending-variant"></b-icon>
                <span></span>
                Ordenar
            </b-navbar-item>

            <b-navbar-item tag="router-link" :to="{ path: '/categorias' }">
                <b-icon icon="archive-outline"></b-icon>
                <span></span>
                Categorías
            </b-navbar-item>
            
            <b-navbar-item tag="router-link" :to="{ path: '/usuarios' }">
                <b-icon icon="account-group"></b-icon>
                <span></span>
                Usuarios
            </b-navbar-item>
            
            <b-navbar-item tag="router-link" :to="{ path: '/reporte-ventas' }">
                <b-icon icon="cash-register"></b-icon>
                <span></span>
                Ventas
            </b-navbar-item>
            
            <b-navbar-item tag="router-link" :to="{ path: '/configurar' }">
                <b-icon icon="application-cog-outline"></b-icon>
                <span></span>
                Configurar
            </b-navbar-item>
        </template>

        <template #end>
            <b-navbar-item tag="div">
                <div class="buttons">
                    
                    <a class="button is-warning" @click="irAPerfil">
                       {{ nombreUsuario }}
                    </a>
                    <a class="button is-light" @click="salir">
                       Salir
                    </a>
                </div>
            </b-navbar-item>
        </template>
    </b-navbar>
</template>
<script>
import HttpService from '../Servicios/HttpService'
import Utiles from '../Servicios/Utiles'

export default ({
    name: 'Encabezado',
    data:() => ({
        expandOnHover: false,
        expandWithDelay: false,
        mobile: "reduce",
        reduce: false,
        datosLocal: {},
        nombreUsuario: "",
        logo: null
    }),

    mounted(){
        this.obtenerDatos()
        this.nombreUsuario = localStorage.getItem('nombreUsuario')
        this.logo = Utiles.generarUrlImagen(this.datosLocal.logo)
    },

    methods: {
        irAPerfil(){
            this.$router.push({
                name: "Perfil",
            })
        },


        obtenerDatos(){
            HttpService.obtener("obtener_datos_local.php")
            .then(resultado => {
                this.datosLocal = resultado
                this.logo = Utiles.generarUrlImagen(this.datosLocal.logo)
            })
        },

        salir(){
            this.$buefy.dialog.confirm({
                title: '¿Salir de la aplicación?',
                message: 'Deseas salir',
                confirmText: 'Sí, salir',
                cancelText: 'No',
                type: 'is-danger',
                hasIcon: true,
                onConfirm: () => {
                    this.$emit("cerrar", false)
                    localStorage.removeItem('logeado')
                    this.$buefy.toast.open('Hasta pronto')
                }
            })
            
        }
    }
})
</script>
