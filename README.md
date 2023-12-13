# PHP CRUD

Creación de un Blog con PHP y PDO

1. Base de Datos:
Primero, creé una base de datos llamada "bdBlog" con tablas como Usuarios, Entradas y Categorías.
Conecté estas tablas siguiendo el esquema proporcionado.

2. Conexión a la Base de Datos:
Para asegurarme de que todo funcione a la perfección, diseñé un script de conexión usando PDO.
Implementé el manejo de excepciones para garantizar una conexión exitosa.

4. Control de Acceso:
Establecí un sistema de control de acceso con dos roles: administrador y usuario.

4. Formularios HTML:
Para recopilar información de manera segura, desarrollé tres formularios HTML con validación y filtrado de datos.

6. Listados y Operaciones:
Creé una página que muestra un listado con todos los elementos de cada una de las tablas. Incluí operaciones como editar, eliminar y ver detalles, adaptadas según el perfil del usuario.

6. Ventanas Modales Bootstrap:
Para confirmar operaciones, como eliminar, utilicé ventanas modales Bootstrap.

8. Detalles de Registros:
Implementé páginas dinámicas para ver detalles completos de usuarios y entradas, enlazadas desde los listados.

8. Imágenes:
Actualicé los scripts para permitir la subida y almacenamiento de imágenes en el directorio 'Images' del servidor al listar, crear o editar usuarios y entradas.

10. Paginación:
Añadí un sistema de paginación completo, con información sobre el número de páginas, registros por página e incluso la opción de ir directamente a una página específica.

10. CKEditor para Edición de Entradas:
Mejoré la experiencia de edición de entradas utilizando CKEditor.

12. Impresión en PDF:
En algunas páginas de listado, implementé la opción de imprimir en PDF.

12. Ordenación por Fechas:
Permití que los usuarios ordenaran el listado de entradas ascendente o descendente por fechas, a través un icono en el encabezado.
