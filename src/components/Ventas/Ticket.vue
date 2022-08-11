<template>
  <section>
    <div id="comprobante" class="comprobante">
      <p>COMPROBANTE DE COMPRA</p>
      <strong>{{ datosLocal.nombre}}</strong><br />
      <img :src="logo" alt="logo">
      <br>
      <span>Teléfono: {{ datosLocal.telefono }}</span><br />
      <p>Fecha: {{ venta.fecha }}</p>
      <p>Atiende:{{ venta.atendio }}</p>
      <p>Cliente: {{ venta.cliente }}</p>
      <table>
        <tr>
          <td colspan="2">Insumo</td>
          <td>Subtotal</td>
        </tr>

        <tr v-for="(insumo, index) in insumos" :key="index">
          <td>{{ insumo.nombre }}</td>
          <td>${{ insumo.precio }} x {{ insumo.cantidad }}</td>
          <td>${{ parseFloat(insumo.cantidad * insumo.precio) }}</td>
        </tr>
      </table>
      <strong>TOTAL: ${{ venta.total }}</strong
      ><br />
      <strong>SU PAGO: ${{ venta.pagado }}</strong>
      <strong>CAMBIO: ${{ venta.pagado - venta.total }}</strong>
    </div>
  </section>
</template>
<script>
import Printd from "printd";

const d = new Printd();
export default {
  name: "Ticket",

  props: ["venta", "insumos", "datosLocal", "logo"],

  data: () => ({
    cssText: `
      #comprobante{
			text-align: center;
			font-family: 'Courier New', monospace;
			font-size: 9px;
      margin: auto 0;
		}

		#comprobante > p {
			margin:0;
		}

		#comprobante > span {
			font-size:8px;
		}

    #comprobante > img {
      width: 50px;
      height:50px;
      margin: auto 0;
    }

		#comprobante > strong{
			font-size: 13px;
			font-weight: bold;
		}
		#comprobante > table, td, th {
			border: 1px solid black;
		}
		#comprobante > table {
			margin-left: auto;
			margin-right: auto;
			
		  border-collapse: collapse;
		}
        `,
  }),

  mounted() {
    this.d = new Printd();
    const { contentWindow } = this.d.getIFrame();
    contentWindow.addEventListener("beforeprint", () =>
      console.log("before print event!")
    );
    contentWindow.addEventListener("afterprint", () =>
      console.log("after print event!")
    );

    this.imprimir();
  },

  methods: {
    imprimir() {
      let zona = document.getElementById("comprobante");
      setTimeout(() => this.d.print(zona, [this.cssText]), 10);
      this.$emit("impreso", false);
    },
  },
};
</script>
<style>
#comprobante {
  width: 300px;
  text-align: center;
  font-family: "Courier New", monospace;
  font-size: 9px;
}
#comprobante > p {
  margin: 0;
}

#comprobante > span {
  font-size: 8px;
}

#comprobante > strong {
  font-size: 13px;
  font-weight: bold;
}
#comprobante > table,
td,
th {
  border: 1px solid black;
}

#comprobante > img {
  width: 70px;
  height: 70px;
}

#comprobante > table {
  margin-left: auto;
  margin-right: auto;

  border-collapse: collapse;
}

.mostrar {
  visibility: visible;
}

.noMostrar {
  visibility: hidden;
}
</style>