<template>
    <section class="hero is-fullheight fondo">
       
        <div class="hero-body">
           
            <div class="container">
        <p class="title is-1 has-text-weight-bold has-text-white has-text-centered titulo">
            Sistema para restaurantes o venta de comida <br>
            <b-icon
                icon="account"
                size="is-large"
                >
            </b-icon>
            Iniciar sesión
        </p> 
            <div class="columns is-centered">
                <div class="column is-5-tablet is-6-desktop is-6-widescreen" >
                <form action="" class="box">
                    <img src="@/assets/logo_principal.png" alt="" height="150" width="150" style="display: block; margin:0 auto;">
                    <p class="title is-3 has-text-centered"></p>
                    <b-field>
                        <b-input placeholder="Correo electrónico"
                            type="email"
                            icon="email"
                            v-model="correo">
                        </b-input>
                    </b-field>

                    <b-field>
                        <b-input type="password"
                            placeholder="Contraseña"
                            v-model="password"
                            password-reveal>
                        </b-input>
                    </b-field>

                    <div class="field has-text-centered">
                    <b-button icon-left="login" type="is-primary" size="is-large" @click="ingresar">Ingresar</b-button>
                    </div>
                </form>
                </div>
            </div>        
            <b-loading :is-full-page="true" v-model="cargando" :can-cancel="false"></b-loading>
            </div>
        </div>
    </section>
</template>
<script>
import HttpService from '../../Servicios/HttpService'

export default {
    name: "Login",

    data: () => ({
        correo: "", 
        password: "",
        cargando:false
    }),

    methods: {
        ingresar(){
            if(!this.correo) {
                this.$buefy.toast.open({
                    message: 'Debes colocar el correo',
                    type: 'is-warning'
                })
                return
            }
            if(!this.password) {
                this.$buefy.toast.open({
                    message: 'Debes colocar la contraseña',
                    type: 'is-warning'
                })
                return
            }
            this.cargando = true
            let payload = {
                correo: this.correo,
                password: this.password
            }

            HttpService.obtenerConDatos(payload, "iniciar_sesion.php")
            .then(log => {
                if(log.resultado === "cambia"){
                   this.$buefy.toast.open({
                        message: 'Datos correctos. Debes cambiar tu contraseña',
                        type: 'is-info'
                    })
                    this.$emit("logeado", log)
                    this.cargando = false
                    return 
                }

                if(log.resultado) {
                    this.$buefy.toast.open({
                        message: 'Datos correctos. Bienvenido',
                        type: 'is-success'
                    })
                    this.$emit("logeado", log)
                    this.cargando = false
                } else {
                    this.$buefy.toast.open({
                        message: 'Datos incorrectos. Verifica tu información',
                        type: 'is-danger'
                    })
                    this.cargando = false
                }
            })
            
        }
    }

}
</script>
<style>

 @import url('https://fonts.googleapis.com/css?family=Amaranth');
.fondo {
background-color: #54008C;
background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 1000'%3E%3Cg fill-opacity='0.83'%3E%3Ccircle fill='%2354008C' cx='50' cy='0' r='50'/%3E%3Cg fill='%235b0092' %3E%3Ccircle cx='0' cy='50' r='50'/%3E%3Ccircle cx='100' cy='50' r='50'/%3E%3C/g%3E%3Ccircle fill='%23620098' cx='50' cy='100' r='50'/%3E%3Cg fill='%2369009d' %3E%3Ccircle cx='0' cy='150' r='50'/%3E%3Ccircle cx='100' cy='150' r='50'/%3E%3C/g%3E%3Ccircle fill='%236f00a3' cx='50' cy='200' r='50'/%3E%3Cg fill='%237600a9' %3E%3Ccircle cx='0' cy='250' r='50'/%3E%3Ccircle cx='100' cy='250' r='50'/%3E%3C/g%3E%3Ccircle fill='%237d00af' cx='50' cy='300' r='50'/%3E%3Cg fill='%238400b4' %3E%3Ccircle cx='0' cy='350' r='50'/%3E%3Ccircle cx='100' cy='350' r='50'/%3E%3C/g%3E%3Ccircle fill='%238b00ba' cx='50' cy='400' r='50'/%3E%3Cg fill='%239200c0' %3E%3Ccircle cx='0' cy='450' r='50'/%3E%3Ccircle cx='100' cy='450' r='50'/%3E%3C/g%3E%3Ccircle fill='%239900c6' cx='50' cy='500' r='50'/%3E%3Cg fill='%239f00cb' %3E%3Ccircle cx='0' cy='550' r='50'/%3E%3Ccircle cx='100' cy='550' r='50'/%3E%3C/g%3E%3Ccircle fill='%23a600d1' cx='50' cy='600' r='50'/%3E%3Cg fill='%23ad00d7' %3E%3Ccircle cx='0' cy='650' r='50'/%3E%3Ccircle cx='100' cy='650' r='50'/%3E%3C/g%3E%3Ccircle fill='%23b400dd' cx='50' cy='700' r='50'/%3E%3Cg fill='%23bb00e2' %3E%3Ccircle cx='0' cy='750' r='50'/%3E%3Ccircle cx='100' cy='750' r='50'/%3E%3C/g%3E%3Ccircle fill='%23c200e8' cx='50' cy='800' r='50'/%3E%3Cg fill='%23c800ee' %3E%3Ccircle cx='0' cy='850' r='50'/%3E%3Ccircle cx='100' cy='850' r='50'/%3E%3C/g%3E%3Ccircle fill='%23cf00f4' cx='50' cy='900' r='50'/%3E%3Cg fill='%23d600f9' %3E%3Ccircle cx='0' cy='950' r='50'/%3E%3Ccircle cx='100' cy='950' r='50'/%3E%3C/g%3E%3Ccircle fill='%23D0F' cx='50' cy='1000' r='50'/%3E%3C/g%3E%3C/svg%3E");
background-attachment: fixed;
background-size: contain;
}

   
.titulo{
    font-family: Amaranth, sans-serif;
    text-align: center;
    font-size: 75px;
    letter-spacing: 2px;
    word-spacing: 3px;
    color: rgb(245, 251, 255);
    text-shadow: rgb(0, 0, 0) -4px 2px 2px;
}

</style>