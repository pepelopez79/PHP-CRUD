<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi pequeño Blog</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN de la hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <!-- Enlace al archivo CSS externo -->
    <link rel="stylesheet" href="CSS/estilos.css">
</head>
<body>
    <div class="container">
        <div class="row mb-3">
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
                <?php 
                    // Mostramos el botón solo si la sesión está iniciada
                    if (isset($_SESSION['usuario'])) {
                        echo '<a href="Acceso/cerrar_sesion.php" class="btn btn-danger" style="width: 150px;">Cerrar Sesión</a>';
                    }
                ?>
            </div>
        </div>
        <div class="text-center">
            <h2>“Mi pequeño Blog”</h2>

            <?php
            if (isset($_SESSION['usuario'])) {
                echo '<div class="list-group">';
                echo '<div class="list-group-item list-group-item-action list-group-item-secondary">Entradas</div>';
                echo '<a href="Entradas/listar_entradas.php" class="list-group-item list-group-item-action">Listar Entradas</a>';
                echo '<a href="Entradas/anadir_entrada.php" class="list-group-item list-group-item-action">Añadir Entrada</a>';

                if ($_SESSION['perfil'] === 'admin') {
                    echo '<br><div class="list-group-item list-group-item-action list-group-item-secondary">Usuarios</div>';
                    echo '<a href="Usuarios/listar_usuarios.php" class="list-group-item list-group-item-action">Listar Usuarios</a>';
                    echo '<a href="Usuarios/anadir_usuario.php" class="list-group-item list-group-item-action">Añadir Usuario</a>';

                    echo '<br><div class="list-group-item list-group-item-action list-group-item-secondary">Categorías</div>';
                    echo '<a href="Categorias/listar_categorias.php" class="list-group-item list-group-item-action">Listar Categorias</a>';
                    echo '<a href="Categorias/anadir_categoria.php" class="list-group-item list-group-item-action">Añadir Categoría</a>';
                }

                echo '<div class="text-center mt-3">';
                echo '</div>';
                echo '</div>';
            } else {
                echo '<a href="Acceso/login.php" class="btn btn-primary" style="width: 150px;">Iniciar Sesión</a>';
            }
            ?>
        </div>
    </div>
</body>
</html>