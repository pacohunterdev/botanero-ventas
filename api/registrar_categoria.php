<?php
include_once "encabezado.php";
$categoria = json_decode(file_get_contents("php://input"));
if (!$categoria) {
    http_response_code(500);
    exit;
}

include_once "funciones.php";

$resultado = registrarCategoria($categoria);
echo json_encode($resultado);



