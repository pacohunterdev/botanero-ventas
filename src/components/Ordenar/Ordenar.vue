<template>
  <section>
    <p class="title is-1 has-text-weight-bold">
      <b-icon icon="pen" size="is-large" type="is-primary"> </b-icon>
      Tomar orden para la mesa #{{ idMesa }}
      <b-field
        label="Nombre del cliente (Opcional)"
        class="is-pulled-right"
        expanded
      >
        <b-input placeholder="Ej. Don Paco" v-model="cliente"></b-input>
      </b-field>
    </p>

    <div class="title is-3 has-text-weight-bold has-text-grey">
      <div class="is-pulled-right">
        <span
          class="has-text-weight-bold has-text-primary"
          style="font-size: 2.5em"
        >
          ${{ total }}
        </span>
        <b-button
          type="is-success"
          size="is-large"
          icon-left="basket-check"
          style="margin-top: 18px"
          @click="realizarOrden"
          v-if="!estaAgregandoInsumos"
        >
          Ordenar
        </b-button>
        <b-button
          type="is-success"
          size="is-large"
          icon-left="basket-check"
          style="margin-top: 18px"
          @click="editarOrden"
          v-if="estaAgregandoInsumos"
        >
          Añadir
        </b-button>
      </div>
    </div>

    <b-field>
      <b-autocomplete
        size="is-large"
        v-model="nombre"
        placeholder="Nombre del platillo o bebida"
        :data="filteredDataObj"
        field="nombre"
        @input="buscarInsumo"
        @select="(option) => agregarInsumoAOrden(option)"
        :clearable="true"
        keep-first
        id="busqueda"
      >
      </b-autocomplete>
    </b-field>

    <div class="columns is-desktop">
      <div
        class="column"
        v-if="insumosOrden.length > 0 || insumosAnteriores.length > 0"
      >
        <p class="has-text-primary size-is-4" v-if="insumosOrden.length > 0">
          <b-icon icon="plus"></b-icon>
          Insumos agregados
        </p>

        <productos-orden 
        :lista="insumosOrden" 
        :tipo="'nuevo'" 
        @modificado="onProductoModificado" 
        @quitar="eliminar"
        v-if="insumosOrden.length > 0"/> 

        <p
          class="has-text-primary size-is-4"
          v-if="insumosAnteriores.length > 0"
        >
          <b-icon icon="basket"></b-icon>
          Insumos servidos
        </p>
        <productos-orden :lista="insumosAnteriores" :tipo="'entregado'" v-if="insumosAnteriores.length > 0"/>
       
      </div>

      <div class="column is-2" v-if="insumos.length > 0">
        <p class="title is-6 has-text-weight-bold has-text-grey">
          También te puede interesar
        </p>
        <div class="">
          <div class="" v-for="insumo in insumos" :key="insumo.id">
            <div class="card">
              <header class="card-header">
                <div class="card-header-title is-size-7">
                  <b-icon
                    size="is-small"
                    icon="noodles"
                    class="has-text-info"
                    v-if="insumo.tipo === 'PLATILLO'"
                  ></b-icon>
                  <b-icon
                    icon="cup"
                    size="is-small"
                    class="has-text-success"
                    v-if="insumo.tipo === 'BEBIDA'"
                  ></b-icon>
                  {{ insumo.nombre }}
                </div>
                <b-button
                  class="mb-1 is-pulled-right"
                  type="is-primary"
                  size="is-small"
                  icon-left="plus"
                  @click="agregarInsumoAOrden(insumo)"
                >
                </b-button>
              </header>
              <div class="card-content">
                <div class="content is-size-7">
                  {{ insumo.descripcion }}
                  <div class="has-text-centered has-text-weight-bold">
                    ${{ insumo.precio }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
import HttpService from "../../Servicios/HttpService";
import ProductosOrden from "./ProductosOrden.vue";
export default {
  name: "Ordenar",
  components: { ProductosOrden },
  data: () => ({
    idMesa: "",
    insumos: [],
    nombre: "",
    insumosAnteriores: [],
    insumosOrden: [],
    total: 0,
    cliente: "",
    estaAgregandoInsumos: false,
  }),

  mounted() {
    document.querySelector("#busqueda").focus();
    this.idMesa = this.$route.params.id;
    this.cliente = (this.$route.params.cliente) ? this.$route.params.cliente : "";
    this.insumosAnteriores = this.$route.params.insumosEnLista;
    if (this.insumosAnteriores.length > 0) {
      this.calcularTotal();
      this.estaAgregandoInsumos = true;
    }
  },

  methods: {
    onProductoModificado(){
      this.calcularTotal()
    },
    editarOrden() {
      let insumos = this.insumosAnteriores.concat(this.insumosOrden);
      let payload = {
        id: this.idMesa,
        insumos: insumos,
        total: this.total,
        atiende: localStorage.getItem("nombreUsuario"),
        idUsuario: localStorage.getItem("idUsuario"),
        cliente: this.cliente,
      };

      HttpService.registrar(payload, "editar_mesa.php").then((resultado) => {
        if (resultado) {
          this.$buefy.toast.open({
            message: "Insumos agregados",
            type: "is-success",
          });
          this.$router.push({
            name: "RealizarOrden",
          });
        }
      });
    },

    realizarOrden() {
      let payload = {
        id: this.idMesa,
        insumos: this.insumosOrden,
        total: this.total,
        atiende: localStorage.getItem("nombreUsuario"),
        idUsuario: localStorage.getItem("idUsuario"),
        cliente: this.cliente,
      };

      HttpService.registrar(payload, "ocupar_mesa.php").then((resultado) => {
        if (resultado) {
          this.$buefy.toast.open({
            message: "Insumos agregados",
            type: "is-success",
          });
          this.$router.push({
            name: "RealizarOrden",
          });
        }
      });
    },

    eliminar(idInsumo) {
      let lista = this.insumosOrden;
      for (let i = 0; i < lista.length; i++) {
        if (lista[i].id === idInsumo) {
          lista.splice(i, 1);
        }
      }
      this.insumosOrden = lista;
      this.calcularTotal();
    },

    calcularTotal() {
      let total = 0;
      let totalAnterior = 0;
      if (this.insumosAnteriores.length > 0) {
        this.insumosAnteriores.forEach((insumo) => {
          totalAnterior +=
            parseFloat(insumo.cantidad) * parseFloat(insumo.precio);
        });
      }
      this.insumosOrden.forEach((insumo) => {
        total += parseFloat(insumo.cantidad) * parseFloat(insumo.precio);
      });
      this.total = total + totalAnterior;
    },

    buscarInsumo() {
      if (this.nombre) {
        HttpService.obtenerConDatos(
          this.nombre,
          "obtener_insumo_nombre.php"
        ).then((resultado) => {
          this.insumos = resultado;
        });
      }
    },

    agregarInsumoAOrden(insumo) {
      if (insumo) {
        let indice = this.verificarSiExisteEnLista(insumo.id);
        if (indice >= 0) {
          this.aumentarCantidad(indice);
        } else {
          this.insumosOrden.push({
            id: insumo.id,
            codigo: insumo.codigo,
            nombre: insumo.nombre,
            precio: insumo.precio,
            caracteristicas: "",
            cantidad: 1,
            estado: "pendiente",
          });
        }
        setTimeout(() => (this.nombre = ""), 10);
        this.calcularTotal();
      }
    },

    verificarSiExisteEnLista(idInsumo) {
      let lista = this.insumosOrden;
      for (let i = 0; i < lista.length; i++) {
        if (lista[i].id === idInsumo) return i;
      }
      return -1;
    },

    aumentarCantidad(indice) {
      let lista = this.insumosOrden;
      let insumo = lista[indice];

      insumo.cantidad++;

      lista[indice] = insumo;
      this.insumosOrden = lista;
    },
  },

  computed: {
    filteredDataObj() {
      return this.insumos.filter((option) => {
        return (
          option.nombre
            .toString()
            .toLowerCase()
            .indexOf(this.nombre.toLowerCase()) >= 0
        );
      });
    },
  },
};
</script>
