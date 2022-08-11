import Chart from 'chart.js'
const MESES = ["Enero" ,"Febrero" ,"Marzo" ,"Abril" ,"Mayo" ,"Junio" ,"Julio" ,"Agosto" ,"Septiembre" ,"Octubre" ,"Noviembre" ,"Diciembre"]
const DIAS = ["Domingo" ,"Lunes" ,"Martes" ,"Miércoles" ,"Jueves" ,"Viernes" ,"Sábado"]

const Utiles =  {
	generarUrlImagen(imagen){
		return "http://localhost/botanero-ventas/api" + imagen
	},
	generarGrafica(array, contenedor, grafica, id){
		this.resetearGrafica(contenedor, grafica, id)
		let lista = array
		//console.log(lista)
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

		const ctx = document.getElementById(id);
		const graficaGenerada = new Chart(ctx, {
		type: "line",
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

	resetearGrafica(contenedor, grafica, id){
		const $contenedorGrafica = document.querySelector(contenedor)
		let $grafica = document.querySelector(grafica)
		$grafica.remove()
		let $nuevaGrafica = document.createElement("canvas")
		$nuevaGrafica.setAttribute("id",id)
		$contenedorGrafica.appendChild($nuevaGrafica)
	},

	calcularTotales(array){
		let total = 0
		array.forEach(elemento => {
			total += parseFloat(elemento.totalVentas)
		});
		return total
	},


	validar(datos) {
       
		let errores = []
        
		for (let [clave, valor] of Object.entries(datos)) {
			if(!valor || !clave) errores.push("Debes colocar el valor de " + clave)
		}
		return errores
	},

	cambiarNumeroANombreMes(arreglo){
		for(let i = 0; i < arreglo.length; i++){
			let pos = arreglo[i].mes 
			arreglo[i].mes = MESES[pos-1]
			
		}
		return arreglo
	},

	cambiarDiaSemana(arreglo){
		for(let i = 0; i < arreglo.length; i++){
			let pos = arreglo[i].numeroDia 
			arreglo[i].dia = DIAS[pos-1]
			
		}
		return arreglo
	},


	

}

export default  Utiles 