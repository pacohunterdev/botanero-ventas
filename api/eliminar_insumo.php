<?php
include_once "encabezado.php";
$idInsumo = json_decode(file_get_contents("php://input"));
if (!$idInsumo) {
    http_response_code(500);
    exit;
}

include_once "funciones.php";

$resultado = eliminarInsumo($idInsumo);
echo json_encode($resultado);
