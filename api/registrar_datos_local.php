<?php

$informacion = json_decode(file_get_contents("php://input"));
if (!$informacion) {
    http_response_code(500);
    exit;
}

include_once "encabezado.php";
include_once "funciones.php";

$hayAjustes = obtenerInformacionLocal();

$imagen = ($informacion->cambiaImagen) ? obtenerImagen($informacion->logo) : explode('api', $informacion->logo)[1];
$informacion->logo = $imagen;

$resultado = (count($hayAjustes) > 0) ?  actualizarInformacionLocal($informacion) : registrarInformacionLocal($informacion);


echo json_encode($resultado);
