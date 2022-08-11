<template>
    <section>
         <b-field label="Nombre del local" >
            <b-input type="text" placeholder="Nombre de tu local" v-model="datos.nombre"></b-input>
        </b-field>
         
        <b-field label="Teléfono" >
            <b-input type="text" placeholder="Número de teléfono" v-model="datos.telefono"></b-input>
        </b-field>

        <b-field label="Número de mesas" >
            <b-numberinput  placeholder="Mesas" :min="1" v-model="datos.numeroMesas"></b-numberinput>
        </b-field>

        <div class="field is-horizontal">
			<div class="field-body">
			    <div class="file is-primary">
			        <label class="file-label">
                        <input class="file-input" type="file" name="resume" @input="pickFile"  ref="fileInput">
                        <span class="file-cta">
                        <span class="file-label">
                            Seleccionar logo
                        </span>
                        </span>
                    </label>
		        </div>
            </div>
        </div> 

        <div id="preview" class="imagen" >
		    <img :src="previewImage">
		</div>


        <div class="has-text-centered">
            <b-button type="is-success" size="is-large" icon-left="check" @click="registrar">Registrar</b-button>
        </div>
    </section>
</template>
<script>
import Utiles from '../../Servicios/Utiles'

export default {
  name: "DatosConfiguracion",
  props: ["datos"],

  data: ()=>({
      previewImage: null,
      cambiaImagen: false
  }),

  async mounted(){
       
    },

  methods: {
    registrar() {
      this.datos.logo = this.previewImage
      this.datos.cambiaImagen = this.cambiaImagen
      this.$emit("registrado", this.datos);
    },

    pickFile() {
        let input = this.$refs.fileInput
        let file = input.files
        if (file && file[0]) {
            let reader = new FileReader
            reader.onload = e => {
                this.previewImage = e.target.result
                this.cambiaImagen = true
            }
            reader.readAsDataURL(file[0])
            this.$emit('input', file[0])
        }
    },

  },

    watch:{
        '$props': {
            handler: function (val, oldVal) { 
                this.previewImage = Utiles.generarUrlImagen( val.datos.logo)
            },
            deep: true
        }
    }

};
</script>
<style>
.imagen {
  width: 250px;
  height: 250px;
  display: block;
  cursor: pointer;
  margin: 0 auto 30px;
  background-size: cover;
  background-position: center center;
}
</style>