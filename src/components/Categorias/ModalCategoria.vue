<template>
    <form action="">
                <div class="modal-card" style="width: 25rem">
                    <header class="modal-card-head">
                        <p class="modal-card-title" >{{ titulo }} categoría</p>
                        <button
                            type="button"
                            class="delete"
                            @click="cerrar"/>
                    </header>
                    <section class="modal-card-body">
               
                        <ul v-if="errores.length > 0">
                            <li class="has-text-danger" v-for="error in errores" :key="error">{{ error }}</li>
                        </ul>
                        <b-field label= "Tipo ">
                            <b-select placeholder="Selecciona"
                            required v-model="categoria.tipo">
                                <option value="PLATILLO">
                                    Platillo
                                </option>
                                <option value="BEBIDA">
                                    Bebida
                                </option>
                            </b-select>
                        </b-field>
                        <b-field label="Nombre">
                            <b-input
                                type="text"
                                placeholder="Nombre de la categoría"
                                v-model="categoria.nombre"
                                required
                                >
                            </b-input>
                        </b-field>

                        <b-field label="Descripción" >
                            <b-input maxlength="200" type="textarea" 
                            v-model="categoria.descripcion"
                            required
                            placeholder="Pequeña descripción de la categoría"></b-input>
                        </b-field>

                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Cerrar"
                            @click="cerrar" />
                        <b-button
                            label="Registrar"
                            type="is-success"
                            @click="registrar"
                            :loading="cargando" />
                    </footer>
                </div>
            </form>
</template>
<script>
import HttpService from '../../Servicios/HttpService'
import Utiles from '../../Servicios/Utiles'
export default {
    name: "ModalCategoria",
    props: ["categoria", "titulo", "tipo"],

    data: () =>({
        cargando: false,
        errores: []
    }),

    methods: {
        cerrar(){
            this.$parent.close();
        },

        registrar(){
            let datos = {tipo: this.categoria.tipo, nombre: this.categoria.nombre, descripcion: this.categoria.descripcion}
            this.errores = Utiles.validar(datos)
            if(this.errores.length > 0) return
            
            let ruta = (this.tipo === "registra") ? "registrar_categoria.php" : "editar_categoria.php"
            this.cargando = true
            HttpService.registrar(this.categoria, ruta)
                .then(registrado => {
                    if(registrado){
                        this.$buefy.toast.open({
                            message: 'Información registrada',
                            type: 'is-success'
                        })
                    
                        this.cargando = false
                        this.$emit("registrado", true)
                    }
                })
            

            
        }
    }
}
</script>