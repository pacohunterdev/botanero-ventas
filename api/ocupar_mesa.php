<?php
include_once "encabezado.php";
$mesa = json_decode(file_get_contents("php://input"));
if (!$mesa) {
    http_response_code(500);
    exit;
}

include_once "funciones.php";

$resultado = ocuparMesa($mesa);
echo json_encode($resultado);



