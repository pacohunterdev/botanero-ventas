<template>
    <section>
        <p class="title is-1 has-text-weight-bold">Editar Insumo</p>
        <b-breadcrumb align="is-left" >
            <b-breadcrumb-item tag='router-link' to="/">Inicio</b-breadcrumb-item>
            <b-breadcrumb-item tag='router-link' to="/insumos">Insumos</b-breadcrumb-item>
        </b-breadcrumb>
        <datos-insumo @registrado="onRegistrado" :insumo="insumo"></datos-insumo>        
        <b-loading :is-full-page="true" v-model="cargando" :can-cancel="false"></b-loading>

    </section>
</template>
<script>
import HttpService from '../../Servicios/HttpService'
import DatosInsumo from './DatosInsumo.vue'

export default ({
    name: "EditarInsumo",
    components: { DatosInsumo },
    data: () => ({
        insumo: {},
        cargando: false
    }),

    mounted() {
        this.cargando = true
        HttpService.obtenerConDatos(this.$route.params.id, "obtener_insumo_id.php")
        .then(resultado => {
            this.insumo = resultado
            this.cargando = false
        })
    },

    methods: {
        onRegistrado(insumo){
            this.insumo = insumo
            this.cargando = true
            HttpService.registrar(this.insumo, "editar_insumo.php")
            .then(editado => {
                if(editado){
                    this.$buefy.toast.open({
                        message: 'Información actualizada',
                        type: 'is-success'
                    })
                    this.cargando = false
                    this.$router.push({
                        name: "Insumos",
                    })
                }
            })
        }
    }
})
</script>
