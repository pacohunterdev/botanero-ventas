<template>
    <section>
        {{ datos }}
        <div id="contenedor">
            <canvas id="grafica"></canvas>
        </div>
    </section>
</template>
<script>
import Chart from 'chart.js'

export default ({
    name: "Grafica",
    props: ["datos", "tipo"],

    data: ()=>({
        datosGrafica: []
    }),

    mounted(){
        
        setTimeout(()=> {
            this.datosGrafica = this.datos
            this.generarGrafica(), 
            20
            })
        
    },

    methods: {
        generarGrafica(){
            this.resetearGrafica()
            let lista = this.datosGrafica
            const labels = lista.map(elemento => {
                return elemento[Object.keys(elemento)[0]]
            })
            const totales = lista.map(elemento => {
                return elemento.totalVentas
            })

            
            const data = {
                labels: labels,
                datasets: [{
                    label: "Total ",
                    data: totales,
                    backgroundColor: "#" + Math.floor(Math.random()*16777215).toString(16),
                    fill: false,
                    tension: 0.1,
                    borderWidth: 3
                }]
            }

            const ctx = document.getElementById('grafica');
            const graficaGenerada = new Chart(ctx, {
            type: this.tipo,
            data: data,
            options: {
                responsive: true,
                lineTension: 1,
                scales: {
                yAxes: [
                    {
                    ticks: {
                        beginAtZero: true,
                        padding: 25
                    }
                    }
                ]
                }
            }
            });

        },

        resetearGrafica(){
            const $contenedorGrafica = document.querySelector("#contenedor")
            let $grafica = document.querySelector("#grafica")
            $grafica.remove()
            let $nuevaGrafica = document.createElement("canvas")
            $nuevaGrafica.setAttribute("id","grafica")
            $contenedorGrafica.appendChild($nuevaGrafica)
        },
    }
})
</script>
