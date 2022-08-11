<?php
include_once "encabezado.php";
include_once "funciones.php";
$filtros = json_decode(file_get_contents("php://input"));
$fechaInicio = (isset($filtros->inicio)) ? $filtros->inicio : date("Y-m-d");
$fechaFin = (isset($filtros->fin)) ? $filtros->fin : date("Y-m-d");
$idUsuario =  (isset($filtros->idUsuario)) ? $filtros->idUsuario : "";

$ventas = obtenerVentas($fechaInicio, $fechaFin, $idUsuario);
$ventasPorUsuario = obtenerVentasPorUsuario($fechaInicio, $fechaFin);

$usuarios = obtenerUsuarios();

foreach($ventas as $venta){
    $venta->insumos = obtenerInsumosVenta($venta->id);
}

echo json_encode([
    "ventas" => $ventas, 
    "ventasPorUsuario" => $ventasPorUsuario,
    "usuarios" => $usuarios
]);


