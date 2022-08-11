<template>
    <section>
        <br>
        <div class="tile is-ancestor">
            <div class="tile is-parent" v-for="(carta, index) in cartas"  :key="index">
                <div class="card is-card-widget tile is-child">
                    <div class="card-header">
                        <div class="card-header-title">
                            <span class="is-pulled-right">
                                <b-button tag="router-link" :to="{ path: carta.ruta }">
                                    <b-icon icon="cogs" size="is-small"></b-icon>
                                </b-button>
                            </span>
                            
                            <p>{{ carta.encabezado}}</p>
                            
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="level is-desktop">
                            <div class="level-item">
                                <div class="is-widget-label">
                                    <h4 class="subtitle is-spaced">{{ carta.titulo }}</h4>
                                    <h3 class="title">{{ carta.total }}</h3>
                                </div>
                            </div>
                            <div class="level-item has-widget-icon">
                                <div class="is-widget-icon">
                                    <span class="icon is-large" :class="carta.colorTexto">
                                        <b-icon :icon="carta.icono" size="is-large"></b-icon>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns is-multiline">
            <div class="column is-one-third">
                <div class="box">
                    <p class="title is-4 has-text-grey ">
                        <b-icon icon="clock-outline"></b-icon>
                        Ventas por hora
                        <span class="tag is-primary is-large is-pulled-right"> ${{totalVentasHora}}</span>

                    </p>
                    <b-field label="Selecciona un periodo de tiempo">
                        <b-datepicker
                            placeholder="Click para seleccionar..."
                            size="is-small"
                            v-model="periodoHoras"
                            @input="busquedaAvanzada('hora')"
                            range>
                        </b-datepicker>
                    </b-field>
                    <div id="contenedor-hora">
                        <canvas id="grafica-hora"></canvas>
                    </div>
                </div> 
            </div>
            <div class="column is-one-third">
                <div class="box">
                    <p class="title is-4 has-text-grey ">
                        <b-icon icon="account"></b-icon>
                        Ventas de usuarios
                        <span class="tag is-primary is-large is-pulled-right"> ${{totalVentasUsuarios}}</span>
                    </p>
                    <b-field label="Selecciona un periodo de tiempo">
                        <b-datepicker
                            placeholder="Click para seleccionar..."
                            size="is-small"
                            v-model="periodoUsuarios"
                            @input="busquedaAvanzada('usuario')"
                            range>
                        </b-datepicker>
                    </b-field>
                    <div id="contenedor-usuarios">
                        <canvas id="grafica-usuarios"></canvas>
                    </div>
                </div> 
            </div>
            <div class="column is-one-third">
                <div class="box">
                    <p class="title is-4 has-text-grey ">
                        <b-icon icon="calendar-week"></b-icon>
                        Ventas de la semana
                        <span class="tag is-primary is-large is-pulled-right"> ${{totalVentasSemana}}</span>
                    </p>
                    
                    <div id="contenedor-semana">
                        <canvas id="grafica-semana"></canvas>
                    </div>
                </div> 
            </div>
        </div>
        <div class="box">
            <p class="title is-4 has-text-grey ">
                <b-icon icon="calendar-month"></b-icon>
                Ventas por año 
                <span class="tag is-primary is-large is-pulled-right"> ${{totalVentasMeses}}</span>
                <b-field label="Selecciona un año">
                    <b-select
                        size="is-small"
                        v-model="anioSeleccionado"
                        @change.native="busquedaAvanzada('mes')"
                        expanded>
                        <option v-for="(anio, index) in listaAnios" 
                        :key="index" :value="anio">
                            {{ anio }}
                        </option>
                    </b-select>
                </b-field>
            </p>
            <div id="contenedor-mes">
                <canvas id="grafica-mes"></canvas>
            </div>
        </div>
        <div class="columns is-multiline">
            <div class="column is-6">
                <div class="box">
                    <div class="title is-4 has-text-grey ">
                        <b-icon icon="food-fork-drink"></b-icon>
                        Insumos más vendidos

                        <b-field class="is-pulled-right">
                            <b-select
                                v-model="limiteSeleccionado"
                                @change.native="busquedaAvanzada('limite')"
                                expanded>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </b-select>
                        </b-field>
                    </div> 

                    <b-table
                        :data="insumosMasVendidos"
                        :bordered="true"
                        :striped="true"
                    >

                        <b-table-column field="icono" label=""  v-slot="props">
                            <b-icon
                            icon="noodles"
                            size="is-small"
                            type="is-info"
                            v-if="props.row.tipo === 'PLATILLO'">
                            </b-icon>

                            <b-icon
                            icon="cup"
                            size="is-small"
                            type="is-success"
                            v-if="props.row.tipo === 'BEBIDA'">
                            </b-icon>
                        </b-table-column>
                        <b-table-column field="nombre" label="Nombre" v-slot="props">
                            {{ props.row.nombre }}
                        </b-table-column>
                        <b-table-column field="categoria" label="Categoria" v-slot="props">
                            {{ props.row.categoria }}
                        </b-table-column>
                        <b-table-column field="total" label="Total" v-slot="props">
                            ${{ props.row.total }}
                        </b-table-column>
                        <b-table-column field="progreso" label="Progreso" v-slot="props">
                            <b-progress 
                            :value="props.row.progreso" 
                            show-value format="percent"
                            :type="{'is-success' : props.row.progreso >= 90,
                            'is-info' : props.row.progreso >= 70 && props.row.progreso < 90,
                            'is-danger' : props.row.progreso < 70}">
                            </b-progress>
                        </b-table-column>
                    </b-table>
                </div>
            </div>
            <div class="column is-6">
                <div class="box">
                    <p class="title is-4 has-text-grey ">
                        <b-icon icon="table-furniture"></b-icon>
                        Mesas más ocupadas
                    </p> 
                    <b-table
                        :data="totalesPorMesa"
                        :bordered="true"
                        :striped="true"
                    >
                        <b-table-column field="idMesa" label="Mesa" v-slot="props">
                            Mesa #{{ props.row.idMesa }}
                        </b-table-column>
                        <b-table-column field="total" label="Total" v-slot="props">
                            ${{ props.row.total }}
                        </b-table-column>
                        <b-table-column field="progreso" label="Progreso" v-slot="props">
                            <b-progress 
                            :value="props.row.progreso" 
                            show-value format="percent"
                            :type="{'is-success' : props.row.progreso >= 90,
                            'is-info' : props.row.progreso >= 70 && props.row.progreso < 90,
                            'is-danger' : props.row.progreso < 70}">
                            </b-progress>
                        </b-table-column>
                    </b-table>
                </div>
            </div>
        </div>
        <b-loading :is-full-page="true" v-model="cargando" :can-cancel="false"></b-loading>

    </section>
</template>
<script>
import HttpService from '../Servicios/HttpService'
import Utiles from '../Servicios/Utiles'


export default ({
    name: "Inicio",

    data: ()=> ({
        ventasSemana: [],
        ventasHora: [],
        ventasMeses: [],
        ventasUsuarios: [],
        insumosMasVendidos: [],
        totalesPorMesa: [],
        resultadoCartas: {},
        cargando: false,
        tipoGrafica: "line",
        cartas: [],
        totalVentasHora: 0,
        totalVentasSemana: 0,
        totalVentasUsuarios: 0,
        totalVentasMeses: 0,
        periodoHoras: [],
        periodoUsuarios: [],
        anioSeleccionado: new Date().getFullYear(),
        listaAnios: [],
        limiteSeleccionado: 5,
        filtros: {
            hora: {inicio: "", fin: ""},
            usuarios: {inicio:"", fin:""}
        }
    }),

    mounted(){
        this.filtros.anio = this.anioSeleccionado
        this.filtros.limite = this.limiteSeleccionado
        this.obtenerDatos()  
        this.llenarListaAnios()
    },

    methods: {

        calcularProgreso(arreglo) {
           
                let mayor = (arreglo[0]) ? arreglo[0].total:0
                arreglo.forEach(elemento => {
                    elemento.progreso = parseInt(elemento.total * 100 / mayor)
                });
                return arreglo
            
            
        },

        busquedaAvanzada(tipo, ){
            switch(tipo) {
                case "hora":
                    this.filtros.hora = {
                        inicio: this.periodoHoras[0].toISOString().substring(0, 10), 
                        fin: this.periodoHoras[1].toISOString().substring(0, 10)
                        }
                    break
                
                case "usuario":
                    this.filtros.usuarios = {
                        inicio: this.periodoUsuarios[0].toISOString().substring(0, 10), 
                        fin: this.periodoUsuarios[1].toISOString().substring(0, 10)
                        }
                    break

                case "mes":
                    this.filtros.anio = this.anioSeleccionado
                    break
                case "limite":
                    this.filtros.limite = this.limiteSeleccionado
            }

            this.obtenerDatos()
        },

        llenarListaAnios() {
            for(let i = 2015; i <= this.anioSeleccionado; i++) {
                this.listaAnios.push(i)
            }
        }, 

        obtenerDatos(){
            this.cargando = true
            HttpService.obtenerConDatos(this.filtros, "inicio.php")
            .then(resultado => { 
                this.ventasSemana = Utiles.cambiarDiaSemana(resultado.ventasDiasSemana)
                this.ventasHora = resultado.ventasHora
                this.ventasMeses = Utiles.cambiarNumeroANombreMes(resultado.ventasMeses)
                this.ventasUsuarios = resultado.ventasUsuario
                this.resultadoCartas = resultado.cartas
                this.insumosMasVendidos = this.calcularProgreso(resultado.insumosMasVendidos)
                this.totalesPorMesa = this.calcularProgreso(resultado.totalesPorMesa)
                
                this.totalVentasHora = Utiles.calcularTotales(this.ventasHora)
                this.totalVentasSemana = Utiles.calcularTotales(this.ventasSemana)
                this.totalVentasUsuarios = Utiles.calcularTotales(this.ventasUsuarios)
                this.totalVentasMeses = Utiles.calcularTotales(this.ventasMeses)

                Utiles.generarGrafica(this.ventasSemana, "#contenedor-semana", "#grafica-semana", "grafica-semana")
                Utiles.generarGrafica(this.ventasHora, "#contenedor-hora", "#grafica-hora", "grafica-hora")
                Utiles.generarGrafica(this.ventasMeses, "#contenedor-mes", "#grafica-mes", "grafica-mes")
                Utiles.generarGrafica(this.ventasUsuarios, "#contenedor-usuarios", "#grafica-usuarios", "grafica-usuarios")
                this.cartas = [
                    {
                        encabezado: "Ventas del día",
                        titulo: "Ventas hoy",
                        total: "$" + this.resultadoCartas.totalVentasDia,
                        icono: "cart-outline",
                        colorTexto: "has-text-info",
                        ruta: "/reporte-ventas"
                    },
                    {
                        encabezado: "Estado mesas",
                        titulo: "Mesas ocupadas",
                        total: this.resultadoCartas.numeroMesasOcupadas,
                        icono: "table-furniture",
                        colorTexto: "has-text-success",
                        ruta: "/realizar-orden"
                    },
                    {
                        encabezado: "Usuarios registrados",
                        titulo: "Usuarios",
                        total: this.resultadoCartas.numeroUsuarios,
                        icono: "account",
                        colorTexto: "has-text-danger",
                        ruta: "/usuarios"
                    },
                    {
                        encabezado: "Insumos registrados",
                        titulo: "Insumos",
                        total: this.resultadoCartas.numeroInsumos,
                        icono: "food-fork-drink",
                        colorTexto: "has-text-warning",
                        ruta: "/insumos"
                    },
                    {
                        encabezado: "Total ventas",
                        titulo: "Ventas",
                        total: "$" + this.resultadoCartas.totalVentas,
                        icono: "cart-outline",
                        colorTexto: "has-text-primary",
                        ruta: "/reporte-ventas"
                    },
                ]
                 this.cargando = false
            })
        },

        
    }
})
</script>
