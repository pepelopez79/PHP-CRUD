<?php
require_once '../config.php';

$msgResultado = "";
$idCategoria = $_GET["id"] ?? null;

try {
    $consultaCategoria = $conexion->prepare("SELECT * FROM CATEGORIAS WHERE IDCAT = :idCategoria");
    $consultaCategoria->bindParam(':idCategoria', $idCategoria);
    $consultaCategoria->execute();
    $categoria = $consultaCategoria->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    $msgResultado = '<div class="alert alert-danger">' . "Error al obtener información de la categoría: " . $ex->getMessage() . '</div>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Categoría</title>
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
                <a href="listar_Categorias.php" class="btn btn-primary" style="margin-left: 20px;">Volver al Listado de Categorías</a>
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
        <p><h2>Detalle de Categoría</h2></p>
        <?php echo $msgResultado; ?>
        <table class="table">
            <tr>
                <th>ID</th>
                <td><?php echo $categoria['IDCAT']; ?></td>
            </tr>
            <tr>
                <th>Nombre</th>
                <td><?php echo $categoria['NOMBRECAT']; ?></td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>