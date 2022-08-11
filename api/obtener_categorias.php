<?php
include_once "encabezado.php";
include_once "funciones.php";

$resultado = obtenerCategorias();
echo json_encode($resultado);