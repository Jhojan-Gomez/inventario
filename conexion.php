<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "inventario";

// Crear conexión
$conn = new mysqli($host, $user, $password, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

echo "Conexión exitosa";

?>