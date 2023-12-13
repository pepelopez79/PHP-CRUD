<?php
require_once '../config.php';

$msgResultado = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $idUsuario = $_POST["idUsuario"];
        $nuevoNick = $_POST["nuevoNick"];
        $nuevoNombre = $_POST["nuevoNombre"];
        $nuevosApellidos = $_POST["nuevosApellidos"];
        $nuevoEmail = $_POST["nuevoEmail"];
        $nuevaContrasenia = $_POST["nuevaContrasenia"];
        $nuevoRol = $_POST["nuevoRol"];

        // Procesar la nueva imagen si se ha subido
        $nuevoAvatar = $_FILES["nuevoAvatar"];

        // Verificar si se subió la imagen sin errores
        if ($nuevoAvatar["error"] == 0) {
            // Asegúrate de que el directorio de subida existe y tiene los permisos adecuados
            $directorioSubida = '../Images/';
            $nombreArchivo = basename($nuevoAvatar["name"]);
            $rutaCompleta = $directorioSubida . $nombreArchivo;

            // Mueve el archivo temporal a la ubicación deseada
            if (move_uploaded_file($nuevoAvatar["tmp_name"], $rutaCompleta)) {
                // Actualizar la información del usuario en la base de datos con la nueva imagen
                $consulta = $conexion->prepare("UPDATE USUARIOS SET NICK = :nuevoNick, NOMBRE = :nuevoNombre, APELLIDOS = :nuevosApellidos, 
                    EMAIL = :nuevoEmail, CONTRASENIA = :nuevaContrasenia, AVATAR = :nuevoAvatar, ROL = :nuevoRol WHERE IDUSER = :idUsuario");
                $consulta->bindParam(':nuevoNick', $nuevoNick);
                $consulta->bindParam(':nuevoNombre', $nuevoNombre);
                $consulta->bindParam(':nuevosApellidos', $nuevosApellidos);
                $consulta->bindParam(':nuevoEmail', $nuevoEmail);
                $consulta->bindParam(':nuevaContrasenia', $nuevaContrasenia);
                $consulta->bindParam(':nuevoAvatar', $nombreArchivo);
                $consulta->bindParam(':nuevoRol', $nuevoRol);
                $consulta->bindParam(':idUsuario', $idUsuario);
                $consulta->execute();

                $msgResultado = '<div class="alert alert-success">' . "Usuario actualizado correctamente." . '</div>';
                header("Location: listar_usuarios.php");
                exit();
            } else {
                throw new Exception("Error al subir la nueva imagen.");
            }
        } else {
            // No se subió una nueva imagen, solo actualiza los otros campos
            $consulta = $conexion->prepare("UPDATE USUARIOS SET NICK = :nuevoNick, NOMBRE = :nuevoNombre, APELLIDOS = :nuevosApellidos, 
                EMAIL = :nuevoEmail, CONTRASENIA = :nuevaContrasenia, ROL = :nuevoRol WHERE IDUSER = :idUsuario");
            $consulta->bindParam(':nuevoNick', $nuevoNick);
            $consulta->bindParam(':nuevoNombre', $nuevoNombre);
            $consulta->bindParam(':nuevosApellidos', $nuevosApellidos);
            $consulta->bindParam(':nuevoEmail', $nuevoEmail);
            $consulta->bindParam(':nuevaContrasenia', $nuevaContrasenia);
            $consulta->bindParam(':nuevoRol', $nuevoRol);
            $consulta->bindParam(':idUsuario', $idUsuario);
            $consulta->execute();

            $msgResultado = '<div class="alert alert-success">' . "Usuario actualizado correctamente." . '</div>';
            header("Location: listar_usuarios.php");
            exit();
        }
    } catch (PDOException $ex) {
        $msgResultado = '<div class="alert alert-danger">' . "Error al actualizar el usuario: " . $ex->getMessage() . '</div>';
    } catch (Exception $ex) {
        $msgResultado = '<div class="alert alert-danger">' . $ex->getMessage() . '</div>';
    }
}

$idUsuario = $_GET["id"] ?? null;

try {
    $consultaUsuario = $conexion->prepare("SELECT * FROM USUARIOS WHERE IDUSER = :idUsuario");
    $consultaUsuario->bindParam(':idUsuario', $idUsuario);
    $consultaUsuario->execute();
    $usuario = $consultaUsuario->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    $msgResultado = '<div class="alert alert-danger">' . "Error al obtener información del usuario: " . $ex->getMessage() . '</div>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
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
            <p><h2>Editar Usuario</h2></p>
            <?php echo $msgResultado; ?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="idUsuario" value="<?php echo $usuario['IDUSER']; ?>">
                <div class="form-group">
                    <label for="nuevoNick">Nuevo Nick:</label>
                    <input type="text" class="form-control" id="nuevoNick" name="nuevoNick" value="<?php echo $usuario['NICK']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="nuevoNombre">Nuevo Nombre:</label>
                    <input type="text" class="form-control" id="nuevoNombre" name="nuevoNombre" value="<?php echo $usuario['NOMBRE']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="nuevosApellidos">Nuevos Apellidos:</label>
                    <input type="text" class="form-control" id="nuevosApellidos" name="nuevosApellidos" value="<?php echo $usuario['APELLIDOS']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="nuevoEmail">Nuevo Email:</label>
                    <input type="email" class="form-control" id="nuevoEmail" name="nuevoEmail" value="<?php echo $usuario['EMAIL']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="nuevaContrasenia">Nueva Contraseña:</label>
                    <input type="password" class="form-control" id="nuevaContrasenia" name="nuevaContrasenia" placeholder="********">
                </div>
                <div class="form-group">
                    <label for="nuevoAvatar">Nuevo Avatar:</label>
                    <input type="file" class="form-control" id="nuevoAvatar" name="nuevoAvatar" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="nuevoRol">Nuevo Rol:</label>
                    <select class="form-control" id="nuevoRol" name="nuevoRol" required>
                        <option value="admin" <?php echo ($usuario['ROL'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="user" <?php echo ($usuario['ROL'] === 'user') ? 'selected' : ''; ?>>User</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Guardar Cambios</button>
                <a href="listar_usuarios.php" class="btn btn-danger" style="margin-top: 10px; margin-left: 20px;">Cancelar</a>
            </form>
        </div>
    </div>
</body>
</html>