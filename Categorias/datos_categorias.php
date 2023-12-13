<?php
require_once '../config.php';
require_once '../paginacion.php';

function obtenerResultados($conexion, $sql) {
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
    $sql = "SELECT * FROM CATEGORIAS";

    // Realiza la consulta a la base de datos
    $resultados = obtenerResultados($conexion, $sql);

    if (isset($resultados['error'])) {
        echo '<div class="alert alert-danger">' . $resultados['error'] . '</div>';
    } else {
        echo '<table class="table table-striped" id="pdfTable">';
        echo '<tr><th>ID</th><th>Nombre</th></tr>';
        foreach ($resultados['resultados'] as $fila) {
            echo '<tr>';
            echo '<td>' . $fila['IDCAT'] . '</td>';
            echo '<td>' . $fila['NOMBRECAT'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
} catch (PDOException $ex) {
    echo '<div class="alert alert-danger">' . "La consulta no pudo realizarse correctamente (" . $ex->getMessage() . ')</div>';
    die();
}
?>