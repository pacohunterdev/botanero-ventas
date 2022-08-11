<template>
  <div>
    <b-loading :is-full-page="true" v-model="cargando" :can-cancel="false"></b-loading>

    <section class="hero is-hero-bar is-main-hero">
      <div class="hero-body">
        <div class="level">
          <div class="level-left">
            <div class="level-item is-hero-avatar-item">
              <div class="image is-user-avatar">
                <img src="../../assets/usuario.png" alt="" />
              </div>
            </div>
          </div>
          <div class="level-item is-hero-content-item">
            <div>
              <h1 class="title is-spaced">Perfil de {{ nombreUsuario }}</h1>
            </div>
          </div>
          <div class="level-right">
            <div class="level-item">
              <b-button
                type="is-primary"
                size="is-large"
                icon-left="lock"
                class="is-pulled-right"
                tag="router-link"
                to="/cambiar-password"
              >
                Cambiar contraseña
              </b-button>
            </div>
          </div>
        </div>
      </div>
    </section>
    <br />
    <div class="columns is-desktop">
      <div class="column is-one third">
        <div class="card">
          <header class="card-header">
            <div class="card-header-title">
              <b-icon icon="calendar-week"></b-icon>
              Tus ventas de la semana
            </div>
            <span class="tag is-primary is-large is-pulled-right">
              ${{ totalVentasSemana }}</span
            >
          </header>
          <div class="card-content">
            <div id="contenedor-semana">
              <canvas id="grafica-semana"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-one third">
        <div class="card">
          <header class="card-header">
            <div class="card-header-title">
              <b-icon icon="calendar-month"></b-icon>
              Tus ventas del mes
            </div>
            <span class="tag is-primary is-large is-pulled-right">
              ${{ totalVentasMes }}</span
            >
          </header>
          <div class="card-content">
            <div class="field is-grouped">
              <p class="control">
                <b-select size="is-small" v-model="mesSeleccionado" @change.native="busquedaAvanzada('mes')">
                  <option value="1">Enero</option>
                  <option value="2">Febrero</option>
                  <option value="3">Marzo</option>
                  <option value="4">Abril</option>
                  <option value="5">Mayo</option>
                  <option value="6">Junio</option>
                  <option value="7">Julio</option>
                  <option value="8">Agosto</option>
                  <option value="9">Septiembre</option>
                  <option value="10">Octubre</option>
                  <option value="11">Noviembre</option>
                  <option value="12">Diciembre</option>
                </b-select>
              </p>
              <p class="control">
                <b-select size="is-small" v-model="anioSeleccionado" @change.native="busquedaAvanzada('anioMes')">
                  <option v-for="anio in listaAnios" :key="anio" :value="anio">
                    {{ anio }}
                  </option>
                </b-select>
              </p>
            </div>
            <div id="contenedor-mes">
              <canvas id="grafica-mes"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-one third">
        <div class="card">
          <header class="card-header">
            <div class="card-header-title">
              <b-icon icon="calendar-blank"></b-icon>
              Tus ventas del año
            </div>
            <span class="tag is-primary is-large is-pulled-right">
              ${{ totalVentasAnio }}</span
            >
          </header>
          <div class="card-content">
            <b-select size="is-small" v-model="anioSeleccionadoGrafica" @change.native="busquedaAvanzada('anio')">
              <option v-for="anio in listaAnios" :key="anio" :value="anio">
                {{ anio }}
              </option>
            </b-select>
            <div id="contenedor-anio">
              <canvas id="grafica-anio"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import HttpService from "../../Servicios/HttpService";
import Utiles from "../../Servicios/Utiles";
export default {
  name: "Perfil",

  data: () => ({
    nombreUsuario: "",
    ventasSemana: [],
    totalVentasSemana: 0,
    ventasMes: [],
    totalVentasMes: 0,
    ventasAnio: [],
    totalVentasAnio: 0,
    filtros: {
      porMes: {},
    },
    mesSeleccionado: new Date().getMonth() + 1,
    anioSeleccionado: new Date().getFullYear(),
    anioSeleccionadoGrafica: new Date().getFullYear(),
    listaAnios: [],
    cargando: false
  }),

  mounted() {
    this.nombreUsuario = localStorage.getItem("nombreUsuario");
    this.filtros.idUsuario = localStorage.getItem("idUsuario");
    this.filtros.porMes.mes = this.mesSeleccionado;
    this.filtros.porMes.anio = this.anioSeleccionado;
    this.filtros.anioSeleccionadoGrafica = this.anioSeleccionadoGrafica;
    this.obtenerDatos();
    this.llenarListaAnios();
  },

  methods: {
    llenarListaAnios() {
      for (let i = 2015; i <= this.anioSeleccionado; i++) {
        this.listaAnios.push(i);
      }
    },

    busquedaAvanzada(tipo) {
      switch (tipo) {
        case "mes":
          this.filtros.porMes.mes = this.mesSeleccionado
          break;

        case "anioMes":
          this.filtros.porMes.anio = this.anioSeleccionado
          break;

        case "anio":
          this.filtros.anioSeleccionadoGrafica = this.anioSeleccionadoGrafica
          break;
        
      }

      this.obtenerDatos();
    },

    obtenerDatos() {
      this.cargando = true
      HttpService.obtenerConDatos(this.filtros, "perfil.php").then(
        (resultado) => {
          this.ventasSemana = Utiles.cambiarDiaSemana(resultado.ventasSemana);
          this.totalVentasSemana = Utiles.calcularTotales(this.ventasSemana);
          this.ventasMes = resultado.ventasMes;
          this.totalVentasMes = Utiles.calcularTotales(this.ventasMes);
          this.ventasAnio = Utiles.cambiarNumeroANombreMes(
            resultado.ventasAnio
          );
          this.totalVentasAnio = Utiles.calcularTotales(this.ventasAnio);

          Utiles.generarGrafica(
            this.ventasSemana,
            "#contenedor-semana",
            "#grafica-semana",
            "grafica-semana"
          );
          Utiles.generarGrafica(
            this.ventasMes,
            "#contenedor-mes",
            "#grafica-mes",
            "grafica-mes"
          );
          Utiles.generarGrafica(
            this.ventasAnio,
            "#contenedor-anio",
            "#grafica-anio",
            "grafica-anio"
          );

          this.cargando = false
        }
      );
    },
  },
};
</script>
<style>
section.hero.is-hero-bar.is-main-hero {
  background-image: url("../../assets/fondo.png");
  background-size: cover;
}

section.hero.is-hero-bar.is-main-hero img {
  width: 6rem;
  height: 6rem;
  border-radius: 9999px;
}
</style>