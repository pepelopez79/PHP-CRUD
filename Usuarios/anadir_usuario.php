<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Usuario</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN de la hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <!-- Enlace al archivo CSS externo -->
    <link rel="stylesheet" href="../CSS/estilos.css">
</head>
<body>
    <div class="container">
        <div class="row mb-3">
            <div class="col">
                <a href="../index.php" class="btn btn-primary" style="width: 80px;">Inicio</a>
            </div>
            <div class="col text-right">
                <?php 
                    if (isset($_SESSION['usuario'])) {
                        echo '<div class="user-info mb-2">';
                        echo '<span class="mr-2 user-name">' . $_SESSION['usuario'] . '</span>';
                        echo '<span class="user-role">(' . $_SESSION['perfil'] . ')</span>';
                        echo '</div>';
                    }                    
                ?>
            </div>
            <div class="col-auto">
                <a href="../Acceso/cerrar_sesion.php" class="btn btn-danger" style="width: 150px;">Cerrar Sesión</a>
            </div>
        </div>
        <div class="text-center">
            <p><h2>Añadir Usuario</h2></p>
            <?php
                // Función para sanizitar y validar los campos
                function test_input($data) {
                    $data = trim($data);
                    $data = stripslashes($data);
                    $data = htmlspecialchars($data);
                    return $data;
                }

                // Verificar si se envió el formulario de usuarios
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    include '../config.php';

                    // Obtener y sanizar los datos del formulario
                    $nick = test_input($_POST["nick"]);
                    $nombre = test_input($_POST["nombre"]);
                    $apellidos = test_input($_POST["apellidos"]);
                    $email = test_input($_POST["email"]);
                    $contrasenia = test_input($_POST["contrasenia"]);
                    $rol = test_input($_POST["rol"]);

                    // Procesar la imagen si se ha subido una nueva
                    $avatar = $_FILES["avatar"];

                    // Verificar si se subió la imagen sin errores
                    if ($avatar["error"] == 0) {
                        // Asegúrate de que el directorio de subida existe y tiene los permisos adecuados
                        $directorioSubida = '../Images/';
                        $nombreArchivo = basename($avatar["name"]);
                        $rutaCompleta = $directorioSubida . $nombreArchivo;

                        // Mueve el archivo temporal a la ubicación deseada
                        if (move_uploaded_file($avatar["tmp_name"], $rutaCompleta)) {
                            // Consultar la base de datos para añadir el usuario
                            $consulta = $conexion->prepare("INSERT INTO USUARIOS (NICK, NOMBRE, APELLIDOS, EMAIL, CONTRASENIA, AVATAR, ROL) VALUES (:nick, :nombre, :apellidos, :email, :contrasenia, :avatar, :rol)");
                            $consulta->bindParam(':nick', $nick);
                            $consulta->bindParam(':nombre', $nombre);
                            $consulta->bindParam(':apellidos', $apellidos);
                            $consulta->bindParam(':email', $email);
                            $consulta->bindParam(':contrasenia', $contrasenia);
                            $consulta->bindParam(':avatar', $nombreArchivo);
                            $consulta->bindParam(':rol', $rol);
                            $consulta->execute();

                            echo '<div class="alert alert-success" role="alert">';
                            echo 'Usuario añadido correctamente.';
                            echo '</div>';
                        } else {
                            throw new Exception("Error al subir la imagen.");
                        }
                    } else {
                        throw new Exception("Error al subir la imagen: " . $avatar["error"]);
                    }
                }
            ?>
            <form action="" method="post" enctype="multipart/form-data">
                <!-- Campos del formulario -->
                <div class="form-group">
                    <label for="nick">Nick:</label>
                    <input type="text" class="form-control" id="nick" name="nick" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="contrasenia">Contraseña:</label>
                    <input type="password" class="form-control" id="contrasenia" name="contrasenia" required>
                </div>
                <div class="form-group">
                    <label for="avatar">Avatar:</label>
                    <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select class="form-control" id="rol" name="rol" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <!-- Botón de envío -->
                <button type="submit" class="btn btn-primary">Añadir Usuario</button>
            </form>
        </div>
    </div>
</body>
</html>