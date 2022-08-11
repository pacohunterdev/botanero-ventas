<?php
include_once "encabezado.php";
$idMesa = json_decode(file_get_contents("php://input"));
if (!$idMesa) {
    http_response_code(500);
    exit;
}

include_once "funciones.php";

$resultado = cancelarMesa($idMesa);

echo json_encode($resultado);