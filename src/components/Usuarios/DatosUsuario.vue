<template>
    <section>
         <ul v-if="errores.length > 0">
            <li class="has-text-danger has-text-centered" v-for="error in errores" :key="error">{{ error }}</li>
        </ul>
        <b-field label="Correo electrónico" >
            <b-input type="email" placeholder="Correo del usuario" v-model="usuario.correo"></b-input>
        </b-field>
        <b-field label="Nombre" >
            <b-input type="text" placeholder="Nombre del usuario" v-model="usuario.nombre"></b-input>
        </b-field>
        <b-field label="Teléfono" >
            <b-input type="text" placeholder="Teléfono del usuario" v-model="usuario.telefono"></b-input>
        </b-field>

        <div class="has-text-centered">
            <b-button type="is-success" size="is-large" icon-left="check" @click="registrar">Registrar</b-button>
        </div>
    </section>
</template>
<script>
import Utiles from '../../Servicios/Utiles'

export default ({
    name: "DatosUsuario",
    props: ["usuario"],

    data: () => ({
        errores: []
    }),

    methods: {
        registrar(){
            let datos = {
                correo: this.usuario.correo,
                nombre: this.usuario.nombre,
                telefono: this.usuario.telefono
            }
            this.errores = Utiles.validar(datos)
            if(this.errores.length > 0) return
            this.$emit("registrado", this.usuario)
        }
    }
})
</script>
