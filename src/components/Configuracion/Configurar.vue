<template>
    <section>
        <p class="title is-1 has-text-weight-bold">
            <b-icon
                icon="application-cog-outline"
                size="is-large"
                type="is-primary">
            </b-icon>
            Configurar 
        </p>
        <datos-configuracion @registrado="onRegistrado" :datos="datosLocal" ></datos-configuracion>
        <b-loading :is-full-page="true" v-model="cargando" :can-cancel="false"></b-loading>

    </section>
</template>
<script>
import DatosConfiguracion from './DatosConfiguracion.vue'
import HttpService from '../../Servicios/HttpService'

export default ({
    name: "Configurar",
    components: { DatosConfiguracion },

    data: ()=>({
        cargando: false,
        datosLocal: {
            nombre: "",
            telefono: "",
            numeroMesas: 1,
        },
    }),

    mounted(){
        this.obtenerInformacion()
        
    },

    methods: {
        onRegistrado(datos){
            this.cargando = true
            this.datosLocal = datos

            HttpService.registrar(this.datosLocal, "registrar_datos_local.php")
            .then(registrado => {
                if(registrado) {
                    this.$buefy.toast.open({
                        message: 'Información actualizada. Recarga la página para ver los cambios',
                        type: 'is-success'
                    })
                    this.obtenerInformacion()
                }
            })
        },
        obtenerInformacion(){
            this.cargando = true
            HttpService.obtener("obtener_datos_local.php")
            .then(resultado => {
                this.datosLocal = resultado
                this.datosLocal.numeroMesas = parseInt(this.datosLocal.numeroMesas)
                this.cargando = false

            })
        }
    }
})
</script>
