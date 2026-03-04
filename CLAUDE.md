# CodeViz Research Context

> **Note**: This file contains research context from CodeViz. Most recent contexts are at the bottom.

---

## Research Query

que ruta se usa para mostrar tabla de usuarios

*Session: 95f6f31d768dad410ef6b9ccb7a8e705 | Generated: 7/23/2025, 10:26:07 AM*

### Analysis Summary

# Rutas para la Tabla de Usuarios

## Rutas Web

La ruta principal para mostrar la tabla de usuarios se define en el archivo de rutas web de Laravel.

### Definición de la Ruta

La ruta para la gestión de usuarios se encuentra en [routes/web.php](routes/web.php).

La ruta principal para mostrar la tabla de usuarios es:

*   **`/usuarios`**: Esta ruta carga la vista que contiene la tabla de usuarios. Está definida en [routes/web.php](routes/web.php:20) como `Route::view('usuarios', 'usuarios')`. Requiere autenticación y el permiso `administrar usuarios`.

Para obtener los datos que se muestran en la tabla de usuarios, se utiliza la siguiente ruta API:

*   **`/usuarios/data`**: Esta ruta es un endpoint de API que devuelve los datos de los usuarios. Es consumida por la tabla en la vista `/usuarios`. Está definida en [routes/web.php](routes/web.php:66) y es manejada por el método `userData` del controlador `UsuariosController`.

### Componentes Involucrados

*   **Vista**: La vista [resources/views/usuarios.blade.php](resources/views/usuarios.blade.php) es la encargada de renderizar la interfaz de la tabla de usuarios.
*   **Controlador**: El [App/Http/Controllers/UsuariosController.php](app/Http/Controllers/UsuariosController.php) maneja la lógica para obtener y procesar los datos de los usuarios a través de su método `userData`.
*   **Modelo**: El modelo [App/Models/Usuario.php](app/Models/Usuario.php) interactúa con la base de datos para recuperar la información de los usuarios.

---

## Research Query

explique el funcionamiento de el controller competencias

*Session: 627034cdf9e90660a9c7b0df460316ae | Generated: 7/23/2025, 10:29:48 AM*

### Analysis Summary

# Funcionamiento del Controller Competencias

El **`CompetenciasController`** [CompetenciasController](app/Http/Controllers/CompetenciasController.php) es un componente clave en la aplicación, encargado de gestionar las operaciones relacionadas con las **`Competencias`**. Su propósito principal es manejar las solicitudes HTTP para la visualización, carga y eliminación de datos de competencias, adaptando el acceso a la información según el rol del usuario (administrador o profesor).

## Componentes Internos y Funcionalidades

El controlador se estructura en varios métodos, cada uno con una responsabilidad específica:

### Propiedades de Estado

*   **`$isAdmin`**: Una propiedad booleana que indica si el usuario autenticado tiene el rol de 'Super-Admin'.
*   **`$isTeacher`**: Una propiedad booleana que indica si el usuario autenticado tiene el rol de 'profesor'.
*   **`$user`**: Almacena el objeto del usuario autenticado.

### Métodos Principales

*   **`getUserData()`** [getUserData()](app/Http/Controllers/CompetenciasController.php:23)
    *   **Propósito**: Este método se encarga de obtener la información del usuario autenticado y establecer las propiedades `$isAdmin`, `$isTeacher` y `$user` del controlador.
    *   **Partes Internas**: Utiliza la fachada `Auth` de Laravel para obtener el usuario actual y sus roles (`hasRole('Super-Admin')`, `hasRole('profesor')`).
    *   **Relaciones Externas**: Depende de la fachada `Auth` de Laravel y del modelo `User` (implícitamente a través de `Auth::user()`).

*   **`loadData()`** [loadData()](app/Http/Controllers/CompetenciasController.php:32)
    *   **Propósito**: Carga los datos de las competencias basándose en el rol del usuario.
    *   **Partes Internas**:
        *   Si el usuario es 'Super-Admin', recupera todas las competencias utilizando el modelo **`Competencia`** [Competencia](app/Models/Competencia.php).
        *   Si el usuario es 'profesor', recupera solo las competencias asociadas a su `profesor_id`.
        *   Si no es ninguno de los anteriores, redirige al usuario a la página anterior.
    *   **Relaciones Externas**: Interactúa con el modelo **`Competencia`** [Competencia](app/Models/Competencia.php) y utiliza el método `getUserData()` para determinar el rol del usuario.

*   **`data()`** [data()](app/Http/Controllers/CompetenciasController.php:45)
    *   **Propósito**: Prepara los datos de las competencias para ser consumidos por una tabla de datos (DataTables), añadiendo columnas personalizadas para checkboxes y acciones.
    *   **Partes Internas**:
        *   Llama a `loadData()` para obtener las competencias.
        *   Utiliza la librería `DataTables()` para transformar la colección de competencias.
        *   Añade una columna `checkbox` con un input HTML para selección.
        *   Añade una columna `actions` con botones de "Edit" y "Delete", incluyendo rutas dinámicas para la edición (`/edit/competencias/{id}`) y un `data-id` para la eliminación.
        *   Define las columnas `checkbox` y `actions` como `rawColumns` para renderizar HTML.
    *   **Relaciones Externas**: Depende de la función global `DataTables()` (generalmente configurada en Laravel para integrar librerías como Yajra DataTables) y del método `loadData()`.

*   **`delete($id)`** [delete($id)](app/Http/Controllers/CompetenciasController.php:64)
    *   **Propósito**: Elimina una competencia específica de la base de datos.
    *   **Partes Internas**:
        *   Busca la competencia por su `$id` utilizando `Competencia::findOrFail($id)`.
        *   Si la encuentra, la elimina con `$competencia->delete()`.
        *   Maneja excepciones (`try-catch`) para retornar una respuesta JSON indicando éxito o fracaso.
    *   **Relaciones Externas**: Interactúa con el modelo **`Competencia`** [Competencia](app/Models/Competencia.php) y devuelve una respuesta JSON.

## Relaciones con Otros Componentes

El **`CompetenciasController`** [CompetenciasController](app/Http/Controllers/CompetenciasController.php) se relaciona con los siguientes elementos del sistema:

*   **Modelos**:
    *   **`Competencia`** [Competencia](app/Models/Competencia.php): El modelo principal con el que interactúa para realizar operaciones CRUD (Crear, Leer, Actualizar, Eliminar) sobre las competencias.
    *   **`Materia`** [Materia](app/Models/Materia.php), **`Usuario`** [Usuario](app/Models/Usuario.php), **`Periodo`** [Periodo](app/Models/Periodo.php): Aunque no se utilizan directamente en los métodos mostrados, están importados, lo que sugiere posibles interacciones en otras partes del controlador o en métodos futuros.
*   **Servicios/Controladores Externos**:
    *   **`calcularNotasController`** [calcularNotasController](app/Http/Controllers/Estudiantes/calcularNotasController.php): Importado en el controlador, lo que indica una posible dependencia o uso de sus funcionalidades en otros métodos no mostrados.
*   **Framework Laravel**:
    *   **`Illuminate\Http\Request`**: Utilizado para manejar las solicitudes HTTP entrantes.
    *   **`Illuminate\Support\Facades\DB`**: Importado, lo que sugiere posibles operaciones directas de base de datos.
    *   **`Illuminate\Support\Facades\Auth`**: Esencial para la autenticación y la autorización basada en roles.
*   **Vistas**:
    *   Las rutas manejadas por este controlador (como `/edit/competencias/{id}`) probablemente renderizan vistas Blade (ej. [competencias.blade.php](resources/views/competencias.blade.php)) para la interfaz de usuario.
*   **Rutas**:
    *   Las funcionalidades del controlador se exponen a través de rutas definidas en los archivos de rutas de Laravel (ej. [web.php](routes/web.php)), que mapean URLs a los métodos del controlador.

