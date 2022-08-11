<?php
include_once "encabezado.php";
$tipoCategoria = json_decode(file_get_contents("php://input"));
if (!$tipoCategoria) {
    http_response_code(500);
    exit;
}

include_once "funciones.php";

$resultado = obtenerCategoriasPorTipo($tipoCategoria);
echo json_encode($resultado);
