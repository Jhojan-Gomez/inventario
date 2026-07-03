<?php
session_start();
include("conexion.php");

/* 🔐 PROTECCIÓN DE SESIÓN */
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

/* 📥 ENTRADA DE PRODUCTO */
if (isset($_GET['entrada'])) {
    $id = $_GET['entrada'];

    mysqli_query($conn, "UPDATE productos SET cantidad = cantidad + 1 WHERE id=$id");

    mysqli_query($conn, "INSERT INTO movimientos (producto_id, tipo, cantidad)
                         VALUES ($id, 'entrada', 1)");

    header("Location: productos.php");
    exit();
}

/* 📤 SALIDA DE PRODUCTO (CON VALIDACIÓN) */
if (isset($_GET['salida'])) {
    $id = $_GET['salida'];

    $res = mysqli_query($conn, "SELECT cantidad FROM productos WHERE id=$id");
    $row = mysqli_fetch_assoc($res);

    if ($row['cantidad'] > 0) {

        mysqli_query($conn, "UPDATE productos SET cantidad = cantidad - 1 WHERE id=$id");

        mysqli_query($conn, "INSERT INTO movimientos (producto_id, tipo, cantidad)
                             VALUES ($id, 'salida', 1)");
    }

    header("Location: productos.php");
    exit();
}

/* ➕ INSERTAR PRODUCTO */
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];

    mysqli_query($conn, "INSERT INTO productos (nombre, cantidad)
                         VALUES ('$nombre', '$cantidad')");

    header("Location: productos.php");
    exit();
}

/* ❌ ELIMINAR PRODUCTO */
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];

    mysqli_query($conn, "DELETE FROM productos WHERE id=$id");

    header("Location: productos.php");
    exit();
}

/* 📋 LISTAR PRODUCTOS */
$result = mysqli_query($conn, "SELECT * FROM productos");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <h2>Gestión de Productos</h2>

    <a href="dashboard.php" class="btn btn-secondary mb-3">Volver</a>

    <!-- FORMULARIO -->
    <form method="POST" class="mb-4">
        <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre producto" required>
        <input type="number" name="cantidad" class="form-control mb-2" placeholder="Cantidad" required>
        <button type="submit" name="guardar" class="btn btn-primary">Guardar</button>
    </form>

    <!-- TABLA -->
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Acciones</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['nombre']; ?></td>
            <td><?php echo $row['cantidad']; ?></td>
            <td>

                <!-- 🔼 ENTRADA -->
                <a href="?entrada=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">+1</a>

                <!-- 🔽 SALIDA -->
                <a href="?salida=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">-1</a>

                <!-- ❌ ELIMINAR -->
                <a href="?eliminar=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('¿Eliminar producto?')">
                   Eliminar
                </a>

            </td>
        </tr>
        <?php } ?>

    </table>

</div>

</body>
</html>