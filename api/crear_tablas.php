<?php
include_once "encabezado.php";
include_once "funciones.php";

$host = "localhost";
$usuario = "root";
$password = "";
$resultados = [];

$crear_bd = "CREATE DATABASE IF NOT EXISTS botanero_ventas";


$sentencias = [
	["tabla"=>"categorias",
	"sentencia" => 
		'CREATE TABLE categorias(
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            tipo ENUM("PLATILLO", "BEBIDA") NOT NULL,
            nombre VARCHAR(50) NOT NULL,
            descripcion VARCHAR(255)
	);'],


	["tabla"=>"insumos",
		"sentencia" =>
		'CREATE TABLE insumos(
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            codigo VARCHAR(100) NOT NULL,
            nombre VARCHAR(100) NOT NULL,
            descripcion VARCHAR(255) NOT NULL,
            precio DECIMAL(6,2) NOT NULL,
            tipo ENUM("PLATILLO", "BEBIDA") NOT NULL,
            categoria BIGINT UNSIGNED NOT NULL
		);'],

	["tabla"=>"informacion_negocio",
		"sentencia" => 'CREATE TABLE informacion_negocio(
            nombre VARCHAR(100),
            telefono VARCHAR(15),
            numeroMesas TINYINT,
            logo VARCHAR(255)
		);'],

	["tabla"=>"usuarios",
		"sentencia" =>
		'CREATE TABLE usuarios(
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            correo VARCHAR(100) NOT NULL,
            nombre VARCHAR(100) NOT NULL,
            telefono VARCHAR(20) NOT NULL,
            password VARCHAR(255) NOT NULL
		);'],

	["tabla"=>"ventas",
		"sentencia" =>
		'CREATE TABLE ventas(
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            idMesa TINYINT NOT NULL,
            cliente VARCHAR(100),
            fecha DATETIME NOT NULL,
            total DECIMAL(6,2) NOT NULL,
            pagado DECIMAL(6,2) NOT NULL,
            idUsuario BIGINT UNSIGNED NOT NULL
		);'],

	["tabla"=>"insumos_venta",
		"sentencia" =>
		'CREATE TABLE insumos_venta(
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            idInsumo BIGINT UNSIGNED NOT NULL,
            precio DECIMAL(6,2) NOT NULL,
            cantidad INT NOT NULL,
            idVenta BIGINT UNSIGNED
		);']
];

$conexion = new PDO("mysql:host=$host", $usuario, $password);
$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$creada_bd = $conexion->exec($crear_bd);
if($creada_bd) array_push($resultados, "Base de datos creada correctamente");
$conexion->exec('USE botanero_ventas');
#Ahora crear las tablas
//$conexion->close();

$conexion = new PDO("mysql:host=$host;dbname=botanero_ventas", $usuario, $password);
$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

foreach ($sentencias as $sentencia) {
	$conexion->exec($sentencia["sentencia"]);

	array_push($resultados, "Tabla {$sentencia["tabla"]} creada correctamente");

}

$conexion = null;
echo json_encode($resultados);

