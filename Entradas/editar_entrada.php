<?php
require_once '../config.php';

$msgResultado = "";

// Intenta obtener las categorías
try {
    $consultaCategorias = $conexion->prepare("SELECT IDCAT, NOMBRECAT FROM CATEGORIAS");
    $consultaCategorias->execute();
    $categorias = $consultaCategorias->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    // Manejar errores de consulta a la base de datos
    $msgResultado = '<div class="alert alert-danger">' . "Error al obtener las categorías: " . $ex->getMessage() . '</div>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $idEntrada = $_POST["idEntrada"];
        $nuevoTitulo = $_POST["nuevoTitulo"];
        $nuevaDescripcion = $_POST["nuevaDescripcion"];
        // Nuevos campos para editar
        $nuevaImagen = $_FILES["nuevaImagen"];
        $nuevaCategoria = $_POST["nuevaCategoria"];

        // Procesar la imagen si se ha subido una nueva
        if ($nuevaImagen["size"] > 0) {
            // Asegúrate de que el directorio de subida existe y tiene los permisos adecuados
            $directorioSubida = '../Images/';
            $nombreArchivo = basename($nuevaImagen["name"]);
            $rutaCompleta = $directorioSubida . $nombreArchivo;

            // Mueve el archivo temporal a la ubicación deseada
            if (move_uploaded_file($nuevaImagen["tmp_name"], $rutaCompleta)) {
                // Actualiza la entrada en la base de datos con la nueva imagen
                $consulta = $conexion->prepare("UPDATE ENTRADAS SET TITULO = :nuevoTitulo, DESCRIPCION = :nuevaDescripcion, IMAGEN = :nuevaImagen, IDCATEGORIA = :nuevaCategoria WHERE IDENT = :idEntrada");
                $consulta->bindParam(':nuevoTitulo', $nuevoTitulo);
                $consulta->bindParam(':nuevaDescripcion', $nuevaDescripcion);
                $consulta->bindParam(':nuevaImagen', $nombreArchivo);
                $consulta->bindParam(':nuevaCategoria', $nuevaCategoria);
                $consulta->bindParam(':idEntrada', $idEntrada);
                $consulta->execute();
            } else {
                throw new Exception("Error al subir la nueva imagen.");
            }
        } else {
            // No se subió una nueva imagen, solo actualiza los otros campos
            $consulta = $conexion->prepare("UPDATE ENTRADAS SET TITULO = :nuevoTitulo, DESCRIPCION = :nuevaDescripcion, IDCATEGORIA = :nuevaCategoria WHERE IDENT = :idEntrada");
            $consulta->bindParam(':nuevoTitulo', $nuevoTitulo);
            $consulta->bindParam(':nuevaDescripcion', $nuevaDescripcion);
            $consulta->bindParam(':nuevaCategoria', $nuevaCategoria);
            $consulta->bindParam(':idEntrada', $idEntrada);
            $consulta->execute();
        }

        $msgResultado = '<div class="alert alert-success">' . "Entrada actualizada correctamente." . '</div>';
        header("Location: listar_entradas.php");
        exit();
    } catch (Exception $ex) {
        $msgResultado = '<div class="alert alert-danger">' . "Error al actualizar la entrada: " . $ex->getMessage() . '</div>';
    }
}

$idEntrada = $_GET["id"] ?? null;

try {
    $consultaEntrada = $conexion->prepare("SELECT e.*, c.NOMBRECAT as NOMBRE_CATEGORIA 
                                           FROM ENTRADAS e 
                                           INNER JOIN CATEGORIAS c ON e.IDCATEGORIA = c.IDCAT 
                                           WHERE e.IDENT = :idEntrada");
    $consultaEntrada->bindParam(':idEntrada', $idEntrada);
    $consultaEntrada->execute();
    $entrada = $consultaEntrada->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    $msgResultado = '<div class="alert alert-danger">' . "Error al obtener información de la entrada: " . $ex->getMessage() . '</div>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Entrada</title>
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
            <p><h2>Editar Entrada</h2></p>
            <?php echo $msgResultado; ?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="idEntrada" value="<?php echo $entrada['IDENT']; ?>">
                <div class="form-group">
                    <label for="nuevaCategoria">Nueva Categoría:</label>
                    <select class="form-control" id="nuevaCategoria" name="nuevaCategoria" required>
                        <?php
                        $idCategoriaEntrada = $entrada['IDCATEGORIA'];
                        $nombreCategoriaEntrada = $entrada['NOMBRE_CATEGORIA'];
                        foreach ($categorias as $categoria) {
                            $idCategoria = $categoria['IDCAT'];
                            $nombreCategoria = $categoria['NOMBRECAT'];
                            $selected = ($idCategoriaEntrada == $idCategoria) ? 'selected' : '';

                            echo "<option value='$idCategoria' $selected>$nombreCategoria</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nuevoTitulo">Nuevo Título:</label>
                    <input type="text" class="form-control" id="nuevoTitulo" name="nuevoTitulo" value="<?php echo $entrada['TITULO']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="nuevaImagen">Nueva Imagen:</label>
                    <input type="file" class="form-control" id="nuevaImagen" name="nuevaImagen" accept="image/*">
                </div>
                <!-- Campo con CKEditor -->
                <div class="form-group">
                    <label for="nuevaDescripcion">Nueva Descripción:</label>
                    <div style="width: 100%;">
                        <textarea id="editor" name="nuevaDescripcion" style="width: 100%; min-height: 100px;"><?php echo $entrada['DESCRIPCION']; ?></textarea>
                    </div>
                </div>
                <!-- Botones -->
                <button type="submit" class="btn btn-primary" style="margin-top: 10px">Guardar Cambios</button>
                <a href="listar_entradas.php" class="btn btn-danger" style="margin-top: 10px; margin-left: 20px;">Cancelar</a>
            </form>
        </div>
    </div>
    <!-- CKEditor script -->
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
</body>
</html>
