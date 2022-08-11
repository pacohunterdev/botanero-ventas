<template>
  <div>
    <br>
    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class="title is-1 has-text-weight-bold">
            <b-icon icon="food-fork-drink" size="is-large" type="is-primary">
            </b-icon>
            Insumos
            <span class="has-text-grey title is-4">
                {{ insumos.length }} resultados
            </span>
          </p>
        </div>
      </div>

      <div class="level-right">
        <p class="level-item">
            <b-button
                type="is-success"
                size="is-large"
                icon-left="plus"
                class="is-pulled-right"
                tag="router-link"
                to="/registrar-insumo"
            >
                Añadir insumo
            </b-button>
        </p>
      </div>
    </nav>

    <div class="field is-grouped">
      <p class="control">
        <b-button
          type="is-primary"
          icon-left="filter-variant"
          @click="filtrar = !filtrar"
          >Filtrar</b-button
        >
      </p>
      <p class="control">
        <b-select v-model="perPage">
          <option value="5">5 por página</option>
          <option value="10">10 por página</option>
          <option value="15">15 por página</option>
          <option value="20">20 por página</option>
        </b-select>
      </p>
    </div>

    <div class="box" v-if="filtrar">
      <b-field grouped group-multiline>
        <b-field label="Tipo de insumo" expanded>
          <b-select v-model="filtros.tipo" @change.native="busquedaAvanzada">
            <option value="" selected>Selecciona el tipo de insumo</option>
            <option value="PLATILLO">Platillo</option>
            <option value="BEBIDA">Bebida</option>
          </b-select>
        </b-field>
        <b-field label="Categoría" expanded>
          <b-select
            v-model="filtros.categoria"
            @change.native="busquedaAvanzada"
          >
            <option value="" selected>Selecciona el tipo de insumo</option>
            <option
              v-for="categoria in categorias"
              :key="categoria.id"
              :value="categoria.id"
            >
              {{ categoria.nombre }}
            </option>
          </b-select>
        </b-field>
        <b-field label="Nombre del insumo" expanded>
          <b-input
            type="text"
            placeholder="Nombre del insumo que deseas buscar"
            v-model="filtros.nombre"
            @input="busquedaAvanzada"
          ></b-input>
        </b-field>
      </b-field>
    </div>

    <b-table
      :data="insumos"
      :paginated="isPaginated"
      :per-page="perPage"
      :bordered="true"
      :striped="true"
      :current-page.sync="currentPage"
      :pagination-simple="isPaginationSimple"
      :pagination-position="paginationPosition"
      :default-sort-direction="defaultSortDirection"
      :pagination-rounded="isPaginationRounded"
      :sort-icon="sortIcon"
      :sort-icon-size="sortIconSize"
      aria-next-label="Next page"
      aria-previous-label="Previous page"
      aria-page-label="Page"
      aria-current-label="Current page"
    >
      <b-table-column field="icono" label="" v-slot="props">
        <b-icon
          icon="noodles"
          size="is-small"
          v-if="props.row.tipo === 'PLATILLO'"
        >
        </b-icon>

        <b-icon icon="cup" size="is-small" v-if="props.row.tipo === 'BEBIDA'">
        </b-icon>
      </b-table-column>

      <b-table-column field="tipo" label="Tipo" sortable v-slot="props">
        {{ props.row.tipo }}
      </b-table-column>

      <b-table-column
        field="codigo"
        label="Código"
        searchable
        sortable
        v-slot="props"
      >
        {{ props.row.codigo }}
      </b-table-column>

      <b-table-column
        field="nombre"
        label="Nombre"
        searchable
        sortable
        v-slot="props"
      >
        {{ props.row.nombre }}
      </b-table-column>

      <b-table-column
        field="descripcion"
        label="Descripción"
        searchable
        sortable
        v-slot="props"
      >
        {{ props.row.descripcion }}
      </b-table-column>

      <b-table-column
        field="categoria"
        label="Categoría"
        searchable
        sortable
        v-slot="props"
      >
        {{ props.row.categoria }}
      </b-table-column>

      <b-table-column field="precio" label="Precio" sortable v-slot="props">
        ${{ props.row.precio }}
      </b-table-column>

      <b-table-column field="acciones" label="Acciones" v-slot="props">
        <div class="field is-grouped">
          <p class="control">
            <b-button
              type="is-danger"
              icon-left="delete"
              @click="eliminar(props.row)"
            >
              Eliminar
            </b-button>
          </p>
          <p class="control">
            <b-button
              type="is-info"
              icon-left="pen"
              @click="editar(props.row.id)"
            >
              Editar
            </b-button>
          </p>
        </div>
      </b-table-column>
    </b-table>
    <b-loading
      :is-full-page="true"
      v-model="cargando"
      :can-cancel="false"
    ></b-loading>
  </div>
</template>
<script>
import HttpService from "../../Servicios/HttpService";

export default {
  name: "Insumos",

  data: () => ({
    filtrar: false,
    insumos: [],
    filtros: {
      tipo: "",
      categoria: "",
      nombre: "",
    },
    categorias: [],
    cargando: false,
    isPaginated: true,
    isPaginationSimple: false,
    isPaginationRounded: true,
    paginationPosition: "bottom",
    defaultSortDirection: "asc",
    sortIcon: "arrow-up",
    sortIconSize: "is-small",
    currentPage: 1,
    perPage: 20,
  }),

  mounted() {
    this.obtenerInsumos();
  },

  methods: {
    busquedaAvanzada() {
      if (this.filtros.tipo === "BEBIDA" || this.filtros.tipo === "PLATILLO") {
        this.obtenerCategorias();
      }

      if (this.filtros.tipo === "") {
        this.categorias = [];
      }

      this.obtenerInsumos();
      this.filtros.categoria = "";
    },

    eliminar(insumo) {
      this.$buefy.dialog.confirm({
        title: "Eliminar el insumo " + insumo.nombre,
        message:
          "¿Seguro que deseas eliminar el insumo? Esta acción no se puede deshacer",
        confirmText: "Sí, eliminar",
        cancelText: "No, salir",
        type: "is-danger",
        hasIcon: true,
        onConfirm: () => {
          HttpService.eliminar("eliminar_insumo.php", insumo.id).then(
            (eliminado) => {
              if (eliminado) {
                this.obtenerInsumos();
                this.$buefy.toast.open("Insumo eliminado");
              }
            }
          );
        },
      });
    },

    editar(idInsumo) {
      this.$router.push({
        name: "EditarInsumo",
        params: { id: idInsumo },
      });
    },

    obtenerInsumos() {
      this.cargando = true;
      HttpService.obtenerConDatos(this.filtros, "obtener_insumos.php").then(
        (datos) => {
          this.insumos = datos;
          this.cargando = false;
        }
      );
    },

    obtenerCategorias() {
      HttpService.obtenerConDatos(
        this.filtros.tipo,
        "obtener_categorias_tipo.php"
      ).then((datos) => {
        this.categorias = datos;
      });
    },
  },
};
</script>