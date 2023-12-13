<?php
require_once '../config.php';

// Mensaje que indicará al usuario si la actualización se realizó correctamente o no
$msgResultado = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se envió el formulario de edición
    try {
        $idCategoria = $_POST["idCategoria"];
        $nuevoNombre = $_POST["nuevoNombre"];

        // Consultar la base de datos para actualizar la categoría
        $consulta = $conexion->prepare("UPDATE CATEGORIAS SET NOMBRECAT = :nuevoNombre WHERE IDCAT = :idCategoria");
        $consulta->bindParam(':nuevoNombre', $nuevoNombre);
        $consulta->bindParam(':idCategoria', $idCategoria);
        $consulta->execute();

        $msgResultado = '<div class="alert alert-success">' . "Categoría actualizada correctamente." . '</div>';
        header("Location: listar_categorias.php");
        exit();
    } catch (PDOException $ex) {
        // Manejar errores de consulta a la base de datos
        $msgResultado = '<div class="alert alert-danger">' . "Error al actualizar la categoría: " . $ex->getMessage() . '</div>';
    }
}

// Obtener el ID de la categoría desde la URL
$idCategoria = $_GET["id"] ?? null;

// Consultar la base de datos para obtener la información de la categoría
try {
    $consultaCategoria = $conexion->prepare("SELECT * FROM CATEGORIAS WHERE IDCAT = :idCategoria");
    $consultaCategoria->bindParam(':idCategoria', $idCategoria);
    $consultaCategoria->execute();
    $categoria = $consultaCategoria->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    // Manejar errores de consulta a la base de datos
    $msgResultado = '<div class="alert alert-danger">' . "Error al obtener información de la categoría: " . $ex->getMessage() . '</div>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoría</title>
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
        <p><h2>Editar Categoría</h2></p>
        <?php echo $msgResultado; ?>
        <form action="" method="post">
            <input type="hidden" name="idCategoria" value="<?php echo $categoria['IDCAT']; ?>">
            <div class="form-group">
                <label for="nuevoNombre">Nuevo Nombre:</label>
                <input type="text" class="form-control" id="nuevoNombre" name="nuevoNombre" value="<?php echo $categoria['NOMBRECAT']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 10px">Guardar Cambios</button>
            <a href="listar_categorias.php" class="btn btn-danger" style="margin-top: 10px; margin-left: 20px;">Cancelar</a>
        </form>
    </div>
</div>
</body>
</html>