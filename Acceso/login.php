<?php
include '../config.php';

// Verificar si se envió el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $usuario = $_POST["usuario"];
    $contrasenia = $_POST["contrasenia"];

    try {
        // Consultar la base de datos para verificar las credenciales
        $consulta = $conexion->prepare("SELECT * FROM USUARIOS WHERE NICK = :usuario AND CONTRASENIA = :contrasenia");
        $consulta->bindParam(':usuario', $usuario);
        $consulta->bindParam(':contrasenia', $contrasenia);
        $consulta->execute();

        // Verificar la autenticación
        if ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
            // Iniciar sesión y establecer el perfil
            session_start();
            $_SESSION["perfil"] = $fila["ROL"];
            $_SESSION["usuario"] = $fila["NICK"];
            $_SESSION["id_usuario"] = $fila["IDUSER"];

            // Redirigir a la página principal
            header('Location: ../index.php');
            exit();
        } else {
            // Si la autenticación falla, mostrar un mensaje de error
            echo '<div class="alert alert-danger" role="alert">';
            echo 'Nombre de usuario o contraseña incorrectos.';
            echo '</div>';
        }
    } catch (PDOException $ex) {
        // Manejar errores de consulta a la base de datos
        echo '<div class="alert alert-danger" role="alert">';
        echo 'Error de consulta a la base de datos: ' . $ex->getMessage();
        echo '</div>';
    }    
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN de la hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <!-- Enlace al archivo CSS externo -->
    <link rel="stylesheet" href="../CSS/estilos.css">
</head>
<body>
<div class="container text-center">
    <div class="cuerpo">
        <h2>Iniciar Sesión</h2>
    </div>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="usuario">Usuario:</label>
            <input type="text" class="form-control" id="usuario" name="usuario" required>
        </div>
        <div class="form-group">
            <label for="contrasenia">Contraseña:</label>
            <input type="password" class="form-control" id="contrasenia" name="contrasenia" required>
        </div>
        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
    </form>
</div>
</body>
</html>
