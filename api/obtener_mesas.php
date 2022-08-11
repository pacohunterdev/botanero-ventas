<?php
include_once "encabezado.php";
include_once "funciones.php";

$resultado = obtenerMesas();

echo json_encode($resultado);