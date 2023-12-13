<?php
require_once '../config.php';
require_once '../paginacion.php';

function obtenerResultadosUsuarios($conexion, $sql) {
    try {
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['resultados' => $resultados];
    } catch (PDOException $ex) {
        return ['error' => "La consulta no pudo realizarse correctamente (" . $ex->getMessage() . ')'];
    }
}

try {
    // Consulta para la tabla USUARIOS
    $sqlUsuarios = "SELECT * FROM USUARIOS";

    // Realiza la consulta a la base de datos para USUARIOS
    $resultadosUsuarios = obtenerResultadosUsuarios($conexion, $sqlUsuarios);

    if (isset($resultadosUsuarios['error'])) {
        echo '<div class="alert alert-danger">' . $resultadosUsuarios['error'] . '</div>';
    } else {
        echo '<table class="table table-striped" id="pdfTableUsuarios">';
        echo '<tr><th>ID</th><th>Nick</th><th>Nombre</th><th>Apellidos</th><th>Email</th><th>Contraseña</th><th>Avatar</th><th>Rol</th></tr>';
        foreach ($resultadosUsuarios['resultados'] as $fila) {
            echo '<tr>';
            echo '<td>' . $fila['IDUSER'] . '</td>';
            echo '<td>' . $fila['NICK'] . '</td>';
            echo '<td>' . $fila['NOMBRE'] . '</td>';
            echo '<td>' . $fila['APELLIDOS'] . '</td>';
            echo '<td>' . $fila['EMAIL'] . '</td>';
            echo '<td>' . $fila['CONTRASENIA'] . '</td>';
            echo '<td>' . $fila['AVATAR'] . '</td>';
            echo '<td>' . $fila['ROL'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

} catch (PDOException $ex) {
    echo '<div class="alert alert-danger">' . "La consulta no pudo realizarse correctamente (" . $ex->getMessage() . ')</div>';
    die();
}
?>
