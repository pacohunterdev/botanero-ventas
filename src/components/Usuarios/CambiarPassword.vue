<template>
    <section class="container">
        <p class="title is-1 has-text-weight-bold">
            Cambiar contraseña
        </p> 

        <article class="message is-info">
            <div class="message-body">
            <p>Consideraciones para la contraseña, debe contener:</p>
            <ul>
            <li>Al menos un número</li>
            <li>Al menos una mayúscula</li>
            <li>Al menos una minúscula</li>
            <li>Mínimo 8 caracteres</li>
            <li>Deberás inciar sesión nuevamente</li>
            </ul>
            </div>
        </article>

        <b-field label="Contraseña actual">
            <b-input type="password"
                placeholder="Contraseña actual"
                v-model="password.actual"
                password-reveal>
            </b-input>
        </b-field>
        
        <b-field label="Nueva contraseña">
            <b-input type="password"
                placeholder="Nueva contraseña"
                v-model="password.nueva"
                password-reveal>
            </b-input>
        </b-field>
        
        <b-field label="Repite la nueva contraseña">
            <b-input type="password"
                placeholder="Repite la nueva contraseña"
                v-model="password.repiteNueva"
                password-reveal>
            </b-input>
        </b-field>

        <ul v-if="errores.length > 0">
            <li class="has-text-danger has-text-centered" v-for="error in errores" :key="error">{{ error }}</li>
        </ul>

        <div class="has-text-centered">
            <b-button type="is-success" size="is-large" icon-left="check" @click="cambiarPassword">Cambiar contraseña</b-button>
        </div>
    </section>
</template>
<script>
import HttpService from '../../Servicios/HttpService'

export default {
    name: "CambiarPassword",

    data: () => ({
        password :{
          actual: "",
          nueva: "",
          repiteNueva: ""
        },
        errores: [],
        cargando: false
    }),

    methods: {

        async cambiarPassword() {
            let continuar =  await this.validarDatos()
            if(continuar) {
                this.errores = []
                this.$buefy.dialog.confirm({
                    message: '¿Cambiar contraseña? Deberás iniciar sesión de nuevo',
                    onConfirm: () => {
                        this.cargando = true
                        let id = localStorage.getItem('idUsuario')
                        let payload = {
                            id: id, 
                            password: this.password.repiteNueva
                        }

                        HttpService.registrar(payload, "cambiar_password.php").
                        then(resultado => {
                            if(resultado){
                                this.$buefy.toast.open('Contraseña actualizada')
                                this.cargando = false
                                localStorage.removeItem('logeado')
                                window.location.reload()
                            }
                        })
                        
                    }
                })
            }
            
        },
        async verificarContraActual() {
            let id = localStorage.getItem('idUsuario')
            let payload = {
                password: this.password.actual, 
                id: id
            }
            let resultado = await HttpService.obtenerConDatos(payload, "verificar_password.php")
            return resultado
        },

  		async validarDatos() {
            let verificarSiCoincide = await this.verificarContraActual()
  			this.errores = [];

            if (!this.password.actual) {
            this.errores.push("Debes colocar la contraseña actual.");
            }
            if(!verificarSiCoincide) {
            this.errores.push("La contraseña actual ingresada no es correcta.");
            }

            if (!this.password.nueva) {
            this.errores.push("Debes colocar la contraseña nueva.");
            }else if (!this.validPass(this.password.nueva)) {
            this.errores.push('La contraseña nueva debe ser valida.');
            }
            if (!this.password.repiteNueva) {
            this.errores.push("Debes repetir la contraseña nueva.");
            }else if (this.password.nueva != this.password.repiteNueva) {
            this.errores.push('Las contraseñas deben coincidir.');
            }

            if (!this.errores.length) {
            return true;
            }

       
  		},

        validPass: function (password) {
            var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/;
            return re.test(password);
        }
    }
}
</script>