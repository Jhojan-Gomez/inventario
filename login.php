<?php
session_start();
include("conexion.php");

if (isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['usuario'] = $usuario;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <h3 class="text-center">Login Inventario</h3>

            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

            <form method="POST">
                <input type="text" name="usuario" class="form-control mb-2" placeholder="Usuario" required>
                <input type="password" name="password" class="form-control mb-2" placeholder="Contraseña" required>
                <button type="submit" name="login" class="btn btn-primary w-100">Entrar</button>
            </form>

        </div>
    </div>
</div>

</body>
</html>
