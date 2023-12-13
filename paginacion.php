<?php

function obtenerResultadosPaginados($conexion, $sql, $regsxpag, $pagina) {
    try {
        $consulta = $conexion->query($sql);

        if (!$consulta) {
            throw new PDOException("La consulta no pudo realizarse correctamente (" . $conexion->errorInfo()[2] . ")");
        }

        // Obtiene el total de registros
        $totalRegistros = $consulta->rowCount();

        // Calcula la cantidad de páginas
        $totalPaginas = ceil($totalRegistros / $regsxpag);

        // Calcula el offset
        $offset = ($pagina - 1) * $regsxpag;

        // Modifica la consulta para incluir paginación
        $sqlPaginado = "$sql LIMIT $offset, $regsxpag";
        $consultaPaginada = $conexion->query($sqlPaginado);

        $resultados = $consultaPaginada->fetchAll(PDO::FETCH_ASSOC);

        return [
            'resultados' => $resultados,
            'totalPaginas' => $totalPaginas,
        ];

    } catch (PDOException $ex) {
        return [
            'error' => "La consulta no pudo realizarse correctamente (" . $ex->getMessage() . ')',
        ];
    }
}