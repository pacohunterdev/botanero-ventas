<?php
include_once "encabezado.php";
include_once "funciones.php";

$datos = json_decode(file_get_contents("php://input"));
$idUsuario = $datos->idUsuario;
$datosMes = $datos->porMes->mes;
$datosMesAnio = $datos->porMes->anio;
$anioSeleccionado = $datos->anioSeleccionadoGrafica;

$ventasSemana = obtenerVentasSemanaDeUsuario($idUsuario);
$ventasMes = obtenerVentasPorDiaMes($datosMes, $datosMesAnio, $idUsuario);
$ventasAnio = obtenerVentasPorMesesDeUsuario($anioSeleccionado, $idUsuario);

echo json_encode([
    "ventasSemana" => $ventasSemana,
    "ventasMes" => $ventasMes,
    "ventasAnio" => $ventasAnio,
]);