<?php
require_once '../config.php';
require_once '../paginacion.php';

$msgResultado = "";

// Obtener el ID del usuario que ha iniciado sesión
$idUsuario = $_SESSION['id_usuario'];
$perfilUsuario = $_SESSION['perfil'];

// Parámetro GET para la dirección de la ordenación
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'asc';

// Parámetro GET para la cantidad de registros por página
$regsxpag = isset($_GET['regsxpag']) ? (int)$_GET['regsxpag'] : 5;

// Parámetro GET para la página actual
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

try {
    // Consulta SQL base con JOIN para obtener información extendida
    $sql = "SELECT e.*, u.NICK as NICKUSUARIO, c.NOMBRECAT FROM ENTRADAS e
            LEFT JOIN USUARIOS u ON e.IDUSUARIO = u.IDUSER
            LEFT JOIN CATEGORIAS c ON e.IDCATEGORIA = c.IDCAT";

    // Condición para filtrar por usuario
    if ($perfilUsuario === 'user') {
        $sql .= " WHERE e.IDUSUARIO = " . (int)$idUsuario;
    } 
    
    // Ordenar y paginar
    $sql .= " ORDER BY e.FECHA " . strtoupper($orden);   

    // Parámetros para la consulta
    $parametros = ($perfilUsuario === 'user') ? [':idUsuario' => $idUsuario] : [];

    // Obtenemos los resultados paginados
    $paginacion = obtenerResultadosPaginados($conexion, $sql, $regsxpag, $pagina);

    if (isset($paginacion['error'])) {
        $msgResultado = '<div class="alert alert-danger">' . $paginacion['error'] . '</div>';
    } else {
        $resultsQuery = $paginacion['resultados'];
        $totalPaginas = $paginacion['totalPaginas'];
    }
} catch (PDOException $ex) {
    $msgResultado = '<div class="alert alert-danger">' . "La consulta no pudo realizarse correctamente (" . $ex->getMessage() . ')</div>';
    die();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Entradas</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN de la hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <!-- Enlace al archivo CSS externo -->
    <link rel="stylesheet" href="../CSS/estilos.css">
    <!-- AJAX -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- html2pdf -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.js"></script>
</head>
<body>
    <div class="container">
        <div class="row mb-3">
            <div class="col">
                <a href="../index.php" class="btn btn-primary" style="width: 80px;">Inicio</a>
                <button class="btn btn-secondary" onclick="generarPDF()" style="margin-left: 10px">Imprimir</button>
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
            <p><h2>Listado de Entradas</h2></p>
            <?php echo $msgResultado;
                echo '<table class="table table-striped" id="pdfTable">';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Nick Usuario</th>';
                echo '<th>Nombre Categoría</th>';
                echo '<th>Título</th>';
                echo '<th>Imagen</th>';
                echo '<th>Descripción</th>';
                echo '<th>';
                echo '<a href="?orden=' . (($orden == 'asc') ? 'desc' : 'asc') . '">';
                echo 'Fecha';
                echo ($orden == 'asc') ? '<span>&#9650;</span>' : '<span>&#9660;</span>';
                echo '</a>';
                echo '</th>';
                echo '<th>Operaciones</th>';
                echo '</tr>';

                foreach ($resultsQuery as $fila) {
                    // Obtener el NICK del usuario
                    $consultaUsuario = $conexion->prepare("SELECT NICK FROM USUARIOS WHERE IDUSER = :idUsuario");
                    $consultaUsuario->bindParam(':idUsuario', $fila['IDUSUARIO']);
                    $consultaUsuario->execute();
                    $usuario = $consultaUsuario->fetch(PDO::FETCH_ASSOC);

                    // Obtener el NOMBRECAT de la categoría
                    $consultaCategoria = $conexion->prepare("SELECT NOMBRECAT FROM CATEGORIAS WHERE IDCAT = :idCategoria");
                    $consultaCategoria->bindParam(':idCategoria', $fila['IDCATEGORIA']);
                    $consultaCategoria->execute();
                    $categoria = $consultaCategoria->fetch(PDO::FETCH_ASSOC);
                    echo '<tr>';
                    echo '<td>' . $fila['IDENT'] . '</td>';
                    echo '<td>' . $usuario['NICK'] . '</td>';
                    echo '<td>' . $categoria['NOMBRECAT'] . '</td>';
                    echo '<td>' . $fila['TITULO'] . '</td>';
                    echo '<td><img src="../Images/' . $fila['IMAGEN'] . '" alt="Imagen" class="avatar-img" style="border-radius: 10%; width: 60px; height: 40px;"></td>';
                    echo '<td>' . $fila['DESCRIPCION'] . '</td>';
                    echo '<td>' . $fila['FECHA'] . '</td>';
                    echo '<td><a href="editar_entrada.php?id=' . $fila['IDENT'] . '">Editar</a> | <a href="eliminar_entrada.php?id=' . $fila['IDENT'] . '">Eliminar</a> | <a href="mostrar_entrada.php?id=' . $fila['IDENT'] . '">Detalle</a></td>';
                    echo '</tr>';
                }
                echo '</table>';
            ?>
        </div>
        <div class="pagination-container" id="pagination-container">
            <div class="arrow" onclick="window.location.href='?pagina=<?php echo max($pagina - 1, 1); ?>&orden=<?php echo $orden; ?>&regsxpag=<?php echo $regsxpag; ?>'">
                ◂ 
            </div>
            <div class="text-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                            <li class="page-item <?php echo ($i == $pagina) ? 'active' : ''; ?>">
                                <a class="page-link" href="?pagina=<?php echo $i; ?>&orden=<?php echo $orden; ?>&regsxpag=<?php echo $regsxpag; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
            <div class="arrow" onclick="window.location.href='?pagina=<?php echo min($pagina + 1, $totalPaginas); ?>&orden=<?php echo $orden; ?>&regsxpag=<?php echo $regsxpag; ?>'">
                ▸
            </div>
        </div>
        <script>
            document.addEventListener('keydown', function (event) {
                if (event.keyCode === 37) {
                    window.location.href = '?pagina=<?php echo max($pagina - 1, 1); ?>&orden=<?php echo $orden; ?>&regsxpag=<?php echo $regsxpag; ?>';
                } else if (event.keyCode === 39) {
                    window.location.href = '?pagina=<?php echo min($pagina + 1, $totalPaginas); ?>&orden=<?php echo $orden; ?>&regsxpag=<?php echo $regsxpag; ?>';
                }
            });
        </script>
        <script>
            function generarPDF() {
                try {
                    // Realizamos una solicitud AJAX al servidor para obtener todos los datos
                    axios.get('datos_entradas.php')
                        .then(function (response) {
                            if (response.data) {
                                var opciones = {
                                    margin: 10,
                                    filename: 'ListadoEntradas.pdf',
                                    image: { type: 'jpeg', quality: 0.98 },
                                    html2canvas: { scale: 2 },
                                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                                };

                                // Removemos las columnas de operaciones antes de convertir el contenido HTML en PDF
                                var contenidoSinOperaciones = response.data.replace(/<th>Operaciones<\/th>/, '').replace(/<td><a[\s\S]+?<\/a> \| <a[\s\S]+?<\/a> \| <a[\s\S]+?<\/a><\/td>/g, '');

                                // Convertimos el contenido HTML (sin operaciones) en PDF
                                var pdf = html2pdf().from(contenidoSinOperaciones).set(opciones).outputPdf();

                                // Descargamos directamente el PDF
                                pdf.save('ListadoEntradas.pdf');
                            } else {
                                alert("No se pudieron obtener datos de la base de datos.");
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                            alert("Error al obtener datos del servidor.");
                        });
                } catch (error) {
                    alert("Error al generar el PDF.");
                }
            }
        </script>
    </div>
</body>
</html>