<?php
include_once "encabezado.php";
$insumo = json_decode(file_get_contents("php://input"));
if (!$insumo) {
    http_response_code(500);
    exit;
}

include_once "funciones.php";

$resultado = obtenerInsumosPorNombre($insumo);
echo json_encode($resultado);
