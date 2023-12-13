<?php
session_start();

// Cerrar sesión
if (session_destroy()) {
    // La sesión se destruyó correctamente
    echo '<div class="alert alert-success" role="alert">';
    echo 'Sesión cerrada correctamente.';
    echo '</div>';
} else {
    // Hubo un problema al destruir la sesión
    echo '<div class="alert alert-danger" role="alert">';
    echo 'Error al cerrar la sesión.';
    echo '</div>';
}

// Redirigir a la página de inicio
header('Location: login.php');
exit();
?>