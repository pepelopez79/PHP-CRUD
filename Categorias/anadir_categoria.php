<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Categoría</title>
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
            <p><h2>Añadir Categoría</h2></p>
            <?php
                // Verificar si se envió el formulario de categorías
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    include '../config.php';

                    // Obtener los datos del formulario
                    $nombreCat = $_POST["nombreCat"];

                    try {
                        // Consultar la base de datos para añadir la categoría
                        $consulta = $conexion->prepare("INSERT INTO CATEGORIAS (NOMBRECAT) VALUES (:nombreCat)");
                        $consulta->bindParam(':nombreCat', $nombreCat);
                        $consulta->execute();

                        echo '<div class="alert alert-success" role="alert">';
                        echo 'Categoría añadida correctamente.';
                        echo '</div>';
                    } catch (PDOException $ex) {
                        // Manejar errores de consulta a la base de datos
                        echo '<div class="alert alert-danger" role="alert">';
                        echo 'Error de consulta a la base de datos: ' . $ex->getMessage();
                        echo '</div>';
                    }
                }
            ?>
            <form action="" method="post">
                <!-- Campos del formulario -->
                <div class="form-group">
                    <label for="nombreCat">Nombre Categoría:</label>
                    <input type="text" class="form-control" id="nombreCat" name="nombreCat" required>
                </div>
                <!-- Botón de envío -->
                <button type="submit" class="btn btn-primary">Añadir Categoría</button>
            </form>
        </div>
    </div>
</body>
</html>