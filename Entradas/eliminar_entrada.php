<?php
require_once '../config.php';

// Mensaje que indicará al usuario si la eliminación se realizó correctamente o no
$msgResultado = "";

// Verificar si se envió una solicitud POST para eliminar la entrada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idEntrada = $_POST["idEntrada"];

    try {
        // Consultar la base de datos para eliminar la entrada
        $consultaEliminar = $conexion->prepare("DELETE FROM ENTRADAS WHERE IDENT = :idEntrada");
        $consultaEliminar->bindParam(':idEntrada', $idEntrada);
        $consultaEliminar->execute();

        // Cambiar el mensaje de éxito antes de redirigir
        $msgResultado = '<div class="alert alert-success">' . "Entrada eliminada correctamente." . '</div>';
        
        // Redirigir al usuario al listado de entradas
        header("Location: listar_entradas.php");
    } catch (PDOException $ex) {
        // Manejar errores de consulta a la base de datos
        $msgResultado = '<div class="alert alert-danger">' . "Error al eliminar la entrada: " . $ex->getMessage() . '</div>';
    }
}

// Obtener el ID de la entrada desde la URL
$idEntrada = $_GET["id"] ?? null;

// Consultar la base de datos para obtener la información de la entrada
try {
    $consultaEntrada = $conexion->prepare("SELECT * FROM ENTRADAS WHERE IDENT = :idEntrada");
    $consultaEntrada->bindParam(':idEntrada', $idEntrada);
    $consultaEntrada->execute();
    $entrada = $consultaEntrada->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    // Manejar errores de consulta a la base de datos
    $msgResultado = '<div class="alert alert-danger">' . "Error al obtener información de la entrada: " . $ex->getMessage() . '</div>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Entrada</title>
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
        <p><h2>Eliminar Entrada</h2></p>
        <?php echo $msgResultado; ?>
        <form action="" method="post">
            <input type="hidden" name="idEntrada" value="<?php echo $entrada['IDENT']; ?>">
            <p>¿Estás seguro de que deseas eliminar la entrada con título "<?php echo $entrada['TITULO']; ?>"?</p>
            <button type="submit" class="btn btn-danger">Eliminar</button>
            <a href="listar_entradas.php" class="btn btn-primary" style="margin-left: 20px;">Cancelar</a>
        </form>
    </div>
</div>
</body>
</html>