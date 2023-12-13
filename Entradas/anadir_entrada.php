<?php
session_start();
include '../config.php';

$msgResultado = "";

// Verificar si se envió el formulario de entradas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID del usuario de la sesión
    $idUsuario = $_SESSION['id_usuario'];

    // Obtener los demás datos del formulario
    $idCategoria = $_POST["idCategoria"];
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];

    try {
        // Procesamos la imagen
        $imagen = $_FILES["imagen"];

        // Verificamos si se subió la imagen sin errores
        if ($imagen["error"] == 0) {
            // Nos aseguramos de que el directorio de subida existe y tiene los permisos adecuados
            $directorioSubida = '../Images/';
            $nombreArchivo = basename($imagen["name"]);
            $rutaCompleta = $directorioSubida . $nombreArchivo;

            // Movemos el archivo temporal a la ubicación deseada
            if (move_uploaded_file($imagen["tmp_name"], $rutaCompleta)) {
                $consulta = $conexion->prepare("INSERT INTO ENTRADAS (IDUSUARIO, IDCATEGORIA, TITULO, IMAGEN, DESCRIPCION, FECHA) VALUES (:idUsuario, :idCategoria, :titulo, :imagen, :descripcion, NOW())");
                $consulta->bindParam(':idUsuario', $idUsuario);
                $consulta->bindParam(':idCategoria', $idCategoria);
                $consulta->bindParam(':titulo', $titulo);
                $consulta->bindParam(':imagen', $nombreArchivo);
                $consulta->bindParam(':descripcion', $descripcion);
                $consulta->execute();

                $msgResultado = '<div class="alert alert-success" role="alert">';
                $msgResultado .= 'Entrada añadida correctamente.';
                $msgResultado .= '</div>';
            } else {
                throw new Exception("Error al subir la imagen.");
            }
        } else {
            throw new Exception("Error al subir la imagen: " . $imagen["error"]);
        }
    } catch (PDOException $ex) {
        // Manejar errores de consulta a la base de datos
        $msgResultado = '<div class="alert alert-danger" role="alert">';
        $msgResultado .= 'Error de consulta a la base de datos: ' . $ex->getMessage();
        $msgResultado .= '</div>';
    } catch (Exception $ex) {
        // Manejar otros errores
        $msgResultado = '<div class="alert alert-danger" role="alert">';
        $msgResultado .= 'Error: ' . $ex->getMessage();
        $msgResultado .= '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Entrada</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN de la hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <!-- Enlace al archivo CSS externo -->
    <link rel="stylesheet" href="../CSS/estilos.css">
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/43.0.0/classic/ckeditor.js"></script>
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
            <p><h2>Añadir Entrada</h2></p>
            <?php echo $msgResultado; ?>
            <form action="" method="post" enctype="multipart/form-data">
                <!-- Campos del formulario -->
                <?php
                    // Obtener categorías desde la base de datos
                    $consultaCategorias = $conexion->query("SELECT IDCAT, NOMBRECAT FROM CATEGORIAS");
                    $categorias = $consultaCategorias->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="form-group">
                    <label for="idCategoria">Categoría:</label>
                    <select class="form-control" id="idCategoria" name="idCategoria" required>
                        <option value="" disabled selected>Selecciona una categoría</option>
                        <?php
                            // Mostrar opciones del desplegable con las categorías
                            foreach ($categorias as $categoria) {
                                echo '<option value="' . $categoria['IDCAT'] . '">' . $categoria['NOMBRECAT'] . '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                </div>
                <!-- Campo con CKEditor -->
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <div>
                        <textarea id="descripcion" name="descripcion" style="width: 100%; min-height: 100px;"></textarea>
                    </div>
                </div>
                <!-- Botón de envío -->
                <button type="submit" class="btn btn-primary">Añadir Entrada</button>
            </form>
        </div>
    </div>
    <!-- CKEditor script -->
    <script>
        ClassicEditor
            .create(document.querySelector('#descripcion'))
            .catch(error => {
                console.error(error);
            });
    </script>
</body>
</html>