<?php
require_once '../config.php';
require_once '../paginacion.php';

function obtenerResultadosEntradas($conexion, $sql) {
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
    // Consulta para la tabla ENTRADAS
    $sqlEntradas = "SELECT * FROM ENTRADAS";

    // Realiza la consulta a la base de datos para ENTRADAS
    $resultadosEntradas = obtenerResultadosEntradas($conexion, $sqlEntradas);

    if (isset($resultadosEntradas['error'])) {
        echo '<div class="alert alert-danger">' . $resultadosEntradas['error'] . '</div>';
    } else {
        echo '<table class="table table-striped" id="pdfTableEntradas">';
        echo '<tr><th>ID</th><th>ID Usuario</th><th>ID Categoría</th><th>Título</th><th>Imagen</th><th>Descripción</th><th>Fecha</th></tr>';
        foreach ($resultadosEntradas['resultados'] as $fila) {
            echo '<tr>';
            echo '<td>' . $fila['IDENT'] . '</td>';
            echo '<td>' . $fila['IDUSUARIO'] . '</td>';
            echo '<td>' . $fila['IDCATEGORIA'] . '</td>';
            echo '<td>' . $fila['TITULO'] . '</td>';
            echo '<td>' . $fila['IMAGEN'] . '</td>';
            echo '<td>' . $fila['DESCRIPCION'] . '</td>';
            echo '<td>' . $fila['FECHA'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

} catch (PDOException $ex) {
    echo '<div class="alert alert-danger">' . "La consulta no pudo realizarse correctamente (" . $ex->getMessage() . ')</div>';
    die();
}
?>