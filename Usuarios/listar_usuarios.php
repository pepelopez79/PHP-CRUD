<?php
require_once '../config.php';
require_once '../paginacion.php';

$msgresultado = "";

try {
    $sql = "SELECT * FROM USUARIOS";
    $parametros = [];

    // Asigna valores predeterminados si las variables no están definidas
    $regsxpag = isset($_GET['regsxpag']) ? (int)$_GET['regsxpag'] : 5;
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $orden = isset($_GET['orden']) ? $_GET['orden'] : 'IDUSER';

    $paginacion = obtenerResultadosPaginados($conexion, $sql, $regsxpag, $pagina);

    if (isset($paginacion['error'])) {
        $msgresultado = '<div class="alert alert-danger">' . $paginacion['error'] . '</div>';
    } else {
        $resultsQuery = $paginacion['resultados'];
        $totalPaginas = $paginacion['totalPaginas'];
    }

} catch (PDOException $ex) {
    $msgresultado = '<div class="alert alert-danger">' . "El listado no pudo realizarse correctamente (" . $ex->getMessage() . ')</div>';
    die();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listado de Usuarios</title>
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
        <p><h2>Listado de Usuarios</h2></p>
        <?php echo $msgresultado; ?>
        <table class="table table-striped" id="pdfTable">
            <tr>
                <th>ID</th>
                <th>Nick</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Avatar</th>
                <th>Rol</th>
                <th>Operaciones</th>
            </tr>
            <?php
                foreach ($resultsQuery as $fila) {
                    echo '<tr>';
                    echo '<td>' . $fila['IDUSER'] . '</td>';
                    echo '<td>' . $fila['NICK'] . '</td>';
                    echo '<td>' . $fila['NOMBRE'] . '</td>';
                    echo '<td>' . $fila['APELLIDOS'] . '</td>';
                    echo '<td>' . $fila['EMAIL'] . '</td>';
                    echo '<td><img src="../Images/' . $fila['AVATAR'] . '" alt="Avatar" class="avatar-img" style="border-radius: 50%; width: 40px; height: 40px;"></td>';
                    echo '<td>' . $fila['ROL'] . '</td>';
                    echo '<td><a href="editar_usuario.php?id=' . $fila['IDUSER'] . '">Editar</a> | <a href="eliminar_usuario.php?id=' . $fila['IDUSER'] . '">Eliminar</a> | <a href="mostrar_usuario.php?id=' . $fila['IDUSER'] . '">Detalle</a></td>';
                    echo '</tr>';
                }
            ?>
        </table>
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
                // Realiza una solicitud AJAX al servidor para obtener todos los datos
                axios.get('datos_usuarios.php')
                    .then(function (response) {
                        // Verifica si la respuesta contiene datos
                        if (response.data) {
                            // Configura las opciones de html2pdf
                            var opciones = {
                                margin: 10,
                                filename: 'ListadoUusarios.pdf',
                                image: { type: 'jpeg', quality: 0.98 },
                                html2canvas: { scale: 2 },
                                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                            };

                            // Remueve las columnas de operaciones antes de convertir el contenido HTML en PDF
                            var contenidoSinOperaciones = response.data.replace(/<th>Operaciones<\/th>/, '').replace(/<td><a[\s\S]+?<\/a> \| <a[\s\S]+?<\/a> \| <a[\s\S]+?<\/a><\/td>/g, '');

                            // Convierte el contenido HTML (sin operaciones) en PDF
                            var pdf = html2pdf().from(contenidoSinOperaciones).set(opciones).outputPdf();

                            // Descarga directamente el PDF
                            pdf.save('ListadoUsuarios.pdf');
                        } else {
                            alert("No se pudieron obtener datos de la base de datos.");
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                        alert("Error al obtener datos del servidor.");
                    });
            } catch (error) {
                // Muestra un mensaje de alerta en caso de error
                alert("Error al generar el PDF.");
            }
        }
    </script>
</div>
</body>
</html>