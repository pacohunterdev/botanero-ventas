<?php
include_once "encabezado.php";
include_once "funciones.php";

$informacion = obtenerInformacionLocal();


if(count($informacion) < 1){
    $informacion = [
        "nombre" => "Botanero",
        "telefono" => "1111111111",
        "numeroMesas" => "5",
        "logo" => "/fotos/logo.png"
    ];
} else {
    $informacion = $informacion[0];
}
echo json_encode($informacion);