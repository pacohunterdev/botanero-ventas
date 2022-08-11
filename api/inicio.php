<?php
include_once "encabezado.php";
include_once "funciones.php";

$filtros = json_decode(file_get_contents("php://input"));

$fechaInicioHora = ($filtros->hora->inicio !== "") ? $filtros->hora->inicio : date("Y-m-d");
$fechaFinHora = ($filtros->hora->fin !== "") ? $filtros->hora->fin : date("Y-m-d");

$fechaInicioUsuarios = ($filtros->usuarios->inicio !== "") ? $filtros->usuarios->inicio : date("Y-m-d");
$fechaFinUsuarios = ($filtros->usuarios->fin !== "") ? $filtros->usuarios->fin : date("Y-m-d");

$ventasDiasSemana = obtenerVentasDiasSemana();
$ventasPorHora = obtenerVentasPorHora($fechaInicioHora, $fechaFinHora);
$ventasMeses = obtenerVentasPorMeses($filtros->anio);
$ventasUsuario = obtenerVentasUsuario($fechaInicioUsuarios, $fechaFinUsuarios);
$insumosMasVendidos = obtenerInsumosMasVendidos($filtros->limite);
$totalesPorMesa = obtenerTotalesPorMesa();

$cartas = [
    "totalVentasDia" =>  obtenerVentasDelDia(),
    "numeroUsuarios" =>  obtenerNumeroUsuarios(),
    "numeroInsumos" =>  obtenerNumeroInsumos(),
    "totalVentas" =>  obtenerTotalVentas(),
    "numeroMesasOcupadas" =>  obtenerNumeroMesasOcupadas(),
    ];

echo json_encode(
	[
		"ventasDiasSemana" => $ventasDiasSemana,
        "ventasHora" => $ventasPorHora,
        "ventasMeses" => $ventasMeses,
        "ventasUsuario" => $ventasUsuario,
        "insumosMasVendidos" => $insumosMasVendidos,
        "totalesPorMesa" => $totalesPorMesa,
        "cartas" => $cartas
	]);