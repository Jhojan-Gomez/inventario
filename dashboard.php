<?php
session_start();
include("conexion.php");

$movimientos = mysqli_query($conn, "
SELECT m.*, p.nombre 
FROM movimientos m
JOIN productos p ON m.producto_id = p.id
ORDER BY m.fecha DESC
LIMIT 10
");

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Total productos
$total = mysqli_query($conn, "SELECT COUNT(*) as total FROM productos");
$totalProductos = mysqli_fetch_assoc($total)['total'];

// Productos con bajo stock
$bajos = mysqli_query($conn, "SELECT * FROM productos WHERE cantidad <= 5");

// Últimos productos
$ultimos = mysqli_query($conn, "SELECT * FROM productos ORDER BY id DESC LIMIT 5");
?>



<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <h2>Dashboard Inventario</h2>
    <p>Bienvenido <?php echo $_SESSION['usuario']; ?></p>

    <a href="productos.php" class="btn btn-primary">Gestionar productos</a>
    <a href="logout.php" class="btn btn-danger">Salir</a>

    <hr>

    <!-- TARJETA -->
    <div class="alert alert-info">
        <h4>Total productos: <?php echo $totalProductos; ?></h4>
    </div>

    <!-- BAJO STOCK -->
    <h5>Productos con bajo stock</h5>
    <ul class="list-group mb-4">
        <?php while($row = mysqli_fetch_assoc($bajos)) { ?>
            <li class="list-group-item">
                <?php echo $row['nombre']; ?> - <?php echo $row['cantidad']; ?>
            </li>
        <?php } ?>
    </ul>

    <!-- ULTIMOS -->
    <h5>Últimos productos</h5>
    <table class="table table-bordered">
        <tr>
            <th>Nombre</th>
            <th>Cantidad</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($ultimos)) { ?>
        <tr>
            <td><?php echo $row['nombre']; ?></td>
            <td><?php echo $row['cantidad']; ?></td>
        </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>



<h5>Últimos movimientos</h5>

<table class="table table-bordered">
    <tr>
        <th>Producto</th>
        <th>Tipo</th>
        <th>Cantidad</th>
        <th>Fecha</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($movimientos)) { ?>
    <tr>
        <td><?php echo $row['nombre']; ?></td>

        <td>
            <?php if ($row['tipo'] == 'entrada') { ?>
                <span class="text-success">Entrada</span>
            <?php } else { ?>
                <span class="text-danger">Salida</span>
            <?php } ?>
        </td>

        <td><?php echo $row['cantidad']; ?></td>
        <td><?php echo $row['fecha']; ?></td>
    </tr>
    <?php } ?>
</table>