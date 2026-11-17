# Planificacion Portales y Comercio Electrónico

INSTITUTO: Superior Nueva Escuela de Diseño y Comunicación
Incorporado a la Enseñanza Oficial A-1002.
CARRERA: Diseño y Programación Web.
Resolución 7-2019-SSPECT. ASIGNATURA: Portales y Comercio Electrónico CARGA HORARIA: 3 horas semanales DOCENTE: Lic. Molinos Joaquín CICLO LECTIVO: 2026


1. Objetivos
Que el alumno logre:
Recuperar y consolidar los conocimientos previos de PHP y MySQL como base para el desarrollo de aplicaciones web mediante frameworks.
Comprender los principios fundamentales de la Programación Orientada a Objetos aplicada al desarrollo web, incluyendo clases, objetos, propiedades, métodos, constructores, encapsulamiento, composición e interfaces.
Comprender qué es un framework de desarrollo web, qué problemáticas resuelve y cuáles son las ventajas de utilizar Laravel para organizar aplicaciones de mediana y alta complejidad.
Comprender y aplicar el patrón arquitectónico MVC (Modelo-Vista-Controlador), reconociendo las responsabilidades específicas de cada capa dentro de una aplicación web.
Comprender el ciclo completo de una petición HTTP dentro de Laravel, desde la recepción de una URL hasta la generación de una respuesta.
Diseñar y utilizar rutas mediante Laravel, relacionando métodos HTTP con operaciones concretas del sistema.
Desarrollar Controllers capaces de coordinar solicitudes, validaciones, acceso a datos y respuestas de la aplicación.
Construir interfaces dinámicas mediante el motor de plantillas Blade, separando correctamente la lógica de presentación de las reglas de negocio.
Diseñar y administrar bases de datos mediante migrations y comprender la utilidad de versionar la estructura de datos de una aplicación.
Utilizar Eloquent ORM para representar entidades del dominio y trabajar con información persistente evitando la dependencia directa de consultas SQL en cada funcionalidad.
Comprender y utilizar relaciones entre modelos tales como hasOne, hasMany y belongsTo, aplicándolas a casos concretos de comercio electrónico.
Implementar sistemas completos de administración de información utilizando lógica CRUD (Create, Read, Update, Delete).
Procesar formularios utilizando los métodos HTTP correspondientes y aplicar validaciones de entrada antes de almacenar o modificar información.
Implementar sistemas de autenticación de usuarios, inicio y cierre de sesión y control de acceso mediante Middleware.
Comprender el funcionamiento de sesiones, datos persistentes y mecanismos de seguridad básicos en aplicaciones web.
Gestionar archivos e imágenes utilizando las herramientas provistas por Laravel, particularmente File y Storage.
Comprender principios básicos de arquitectura de software orientados a mantener responsabilidades claras y código reutilizable.
Introducir el uso de interfaces, inyección de dependencias, Service Container, Service Providers y patrones de diseño como herramientas para desacoplar componentes de una aplicación.
Integrar servicios externos mediante APIs, comprendiendo el funcionamiento general de solicitudes, respuestas, autenticación y procesamiento de información proveniente de terceros.
Implementar en el proyecto integrador funcionalidades vinculadas con pagos electrónicos, logística y otros servicios necesarios para un e-commerce.
Aplicar criterios básicos de seguridad, validación, control de permisos y protección de datos en todas las etapas de desarrollo.
Diseñar y desarrollar un e-commerce funcional mediante Laravel, integrando catálogo, usuarios, productos, categorías, carrito, pedidos, administración, persistencia de datos e integraciones externas.
Estos objetivos retoman los conocimientos adquiridos previamente por los estudiantes en PHP y MySQL y los trasladan hacia un entorno profesional basado en frameworks, arquitectura MVC, programación orientada a objetos y desarrollo de aplicaciones web completas.

2. Contenidos teóricos
Unidad 1: Fundamentos de Laravel, Programación Orientada a Objetos y arquitectura MVC
La primera unidad funcionará como transición entre los conocimientos adquiridos previamente en PHP y MySQL y el desarrollo mediante Laravel.
Se realizará un repaso de los fundamentos necesarios para comprender el funcionamiento del framework: variables, estructuras de control, arrays, funciones, formularios, métodos GET y POST, bases de datos relacionales, claves primarias y foráneas, consultas CRUD y relaciones entre tablas.
Se reforzarán los fundamentos de Programación Orientada a Objetos, trabajando clases, objetos, instancias, propiedades, métodos, constructor, $this, encapsulamiento, composición e interfaces.
Se introducirá el concepto de framework de desarrollo web y se analizarán sus principales ventajas y desventajas frente al desarrollo con PHP sin framework.
Se profundizará especialmente en el patrón MVC (Modelo-Vista-Controlador) como principio de organización del código. Los estudiantes deberán comprender no solamente su definición, sino también la responsabilidad específica de cada componente.
Se trabajará sobre el flujo:
Route → Controller → Model → View → Response
y su aplicación a casos concretos de un e-commerce.
Contenidos principales
Repaso de PHP.
Repaso de MySQL.
CRUD mediante SQL.
Relaciones entre tablas.
JOIN.
Consultas seguras.
Programación Orientada a Objetos.
Clases y objetos.
Propiedades y métodos.
Constructor.
Encapsulamiento.
Composición.
Interfaces.
Introducción a frameworks.
Introducción a Laravel.
Instalación y estructura de un proyecto Laravel.
Arquitectura MVC.
Ciclo de una petición HTTP.
Routes.
Controllers.
Models.
Views.
Blade.
Métodos HTTP: GET, POST, PUT/PATCH y DELETE.
Introducción a rutas RESTful.
La unidad buscará que los alumnos comprendan que Laravel no reemplaza PHP ni las bases de datos, sino que proporciona una arquitectura para organizar sus responsabilidades.

Unidad 2: Persistencia de datos, Eloquent y desarrollo de CRUD
En esta unidad los estudiantes comenzarán a desarrollar funcionalidades concretas utilizando Laravel.
Se abordará la creación y modificación de estructuras de bases de datos mediante migrations y el uso de Eloquent ORM como mecanismo principal de interacción entre la aplicación y MySQL.
Se establecerá la relación conceptual entre una tabla de la base de datos, un registro y una instancia de un Model.
Se desarrollarán relaciones entre entidades propias de un e-commerce, tales como productos, categorías, usuarios, pedidos e ítems de pedido.
Se trabajará en profundidad sobre la implementación de un CRUD completo, relacionando cada acción del usuario con una Route, un método de Controller, un Model y una View correspondiente.
Contenidos principales
Artisan.
Migrations.
Rollback.
Seeders.
Models.
Eloquent ORM.
$fillable.
Creación y actualización mediante Models.
Consultas mediante Eloquent.
Collections.
find().
findOrFail().
where().
orderBy().
get().
Relaciones entre modelos.
belongsTo.
hasMany.
Claves foráneas.
Route Model Binding.
Resource Controllers.
Rutas RESTful.
CRUD completo.
index().
show().
create().
store().
edit().
update().
destroy().
Formularios Blade.
Validación de formularios.
Mensajes de error.
Redirecciones.
Flash messages.
Paginación.
Búsqueda y filtrado.
El objetivo será que los estudiantes puedan desarrollar un módulo completo de administración de productos y categorías, comprendiendo el recorrido completo de la información dentro del patrón MVC.

Unidad 3: Autenticación, autorización y manejo de archivos
Esta unidad incorporará funcionalidades necesarias para transformar una aplicación CRUD en un sistema real con distintos tipos de usuarios y accesos.
Se trabajará sobre autenticación, sesiones, login y logout, diferenciando autenticación de autorización.
Se introducirá el concepto de Middleware como mecanismo encargado de interceptar solicitudes y verificar determinadas condiciones antes de permitir el acceso a una funcionalidad.
Se aplicarán estos conceptos al panel administrativo del e-commerce, restringiendo determinadas operaciones a usuarios autenticados o autorizados.
También se trabajará sobre almacenamiento de archivos e imágenes, necesario particularmente para la gestión del catálogo de productos.
Contenidos principales
Autenticación.
Registro de usuarios.
Login.
Logout.
Sesiones.
Middleware.
Protección de rutas.
Grupos de rutas.
Roles y permisos básicos.
Validación de usuarios.
Manejo de archivos.
File.
Storage.
Carga de imágenes.
Validación de archivos.
Almacenamiento y recuperación de imágenes.
Actualización y eliminación de archivos.
Seguridad básica en formularios.
CSRF.
Cookies.
Introducción al uso seguro de información persistente.
Práctica principal
Implementación de:
login y logout;
acceso restringido al panel administrativo;
CRUD de productos;
carga de imágenes;
administración de contenido según permisos.

Unidad 4: Arquitectura, servicios e integración de APIs
Una vez consolidada la estructura principal de la aplicación, se introducirán conceptos orientados a la construcción de sistemas más desacoplados y mantenibles.
Se retomará el concepto de interfaces y se introducirá el uso de servicios para separar determinadas responsabilidades de los Controllers y Models.
Se trabajará conceptualmente sobre Dependency Injection, Service Container y Service Providers, relacionándolos con situaciones reales del proyecto.
La integración de medios de pago permitirá demostrar concretamente la utilidad de estas herramientas.
Por ejemplo, el sistema podrá trabajar sobre una abstracción:
PaymentGateway
que posteriormente pueda ser implementada por diferentes proveedores.
Contenidos principales
Interfaces.
Dependency Injection.
Service Container.
Service Providers.
Introducción al patrón Repository.
Servicios.
Separación de responsabilidades.
Integraciones mediante API.
Solicitudes HTTP hacia servicios externos.
Procesamiento de respuestas.
Manejo de errores.
Variables de entorno.
Configuración segura de credenciales.
Mercado Pago.
PayPal.
Servicios de logística.
OCA o Correo Argentino.
Google reCAPTCHA.
Cookies.
SSL y HTTPS.
Buenas prácticas de seguridad.
No se buscará que los estudiantes memoricen arquitecturas avanzadas, sino que comprendan progresivamente por qué una aplicación real necesita desacoplar responsabilidades.

Unidad 5: Proyecto Integrador – E-commerce funcional
La etapa final de la materia estará orientada a integrar los conocimientos adquiridos mediante el desarrollo de un e-commerce funcional utilizando Laravel y MySQL.
Cada estudiante trabajará progresivamente sobre una temática de comercio electrónico elegida durante las primeras clases.
El proyecto no deberá resolverse como una colección aislada de páginas, sino como una aplicación web completa basada en MVC, persistencia de datos y lógica de negocio.
Como mínimo deberá contemplar:
Home.
Catálogo de productos.
Categorías.
Detalle de producto.
Gestión de stock.
Registro de usuarios.
Login y logout.
Carrito de compras.
Checkout.
Generación de pedidos.
Detalle de pedidos.
Panel de administración.
CRUD de productos.
CRUD de categorías.
Carga de imágenes.
Validaciones.
Control de acceso.
Persistencia de información mediante MySQL.
Models y relaciones Eloquent.
Integración de al menos un servicio externo vinculado al proceso de compra.
Dependiendo del avance general del curso y del alcance de cada proyecto, podrán incorporarse también:
pasarela de pago;
cálculo de envío;
recuperación de contraseña;
estados de pedido;
filtros de catálogo;
buscador;
administración de usuarios;
otras funcionalidades acordes a la temática elegida.
La instancia final deberá incluir una demostración funcional del sistema y defensa de las decisiones técnicas tomadas.

3. Trabajos prácticos
La materia tendrá una orientación marcadamente práctica y progresiva.
Los trabajos no serán entendidos como ejercicios aislados, sino como instancias que permitan construir gradualmente las capacidades necesarias para desarrollar el Proyecto Integrador.
Se prevén las siguientes instancias:
Trabajo Práctico N.º 1 – Diseño inicial del e-commerce
Cada estudiante deberá:
definir la temática de su e-commerce;
identificar su público y productos principales;
proponer al menos 8 campos para la tabla products;
definir al menos 3 tablas relacionadas;
justificar las relaciones entre ellas;
desarrollar una primera clase PHP Product;
implementar constructor, propiedades y método hasStock();
crear instancias simples para comprobar su funcionamiento.
Este trabajo funcionará como primera aproximación al dominio del Proyecto Integrador.

Trabajo Práctico N.º 2 – Diseño MVC y CRUD
Los estudiantes deberán trasladar las entidades definidas inicialmente hacia una estructura de Laravel.
Se trabajará sobre:
Models;
migrations;
relaciones;
Routes;
Controllers;
Views;
formularios;
validaciones;
implementación de CRUD.
El objetivo será construir el módulo de administración principal del catálogo.

Trabajo Práctico N.º 3 – Usuarios y funcionalidades del e-commerce
Se incorporarán:
autenticación;
sesiones;
Middleware;
permisos;
carga de imágenes;
carrito;
pedidos;
relaciones entre usuarios, pedidos y productos.

Proyecto Integrador
El Proyecto Integrador consistirá en el desarrollo individual de un e-commerce funcional mediante Laravel y MySQL.
La producción deberá integrar los contenidos trabajados durante el cuatrimestre y demostrar una correcta aplicación de:
Programación Orientada a Objetos;
MVC;
bases de datos relacionales;
Eloquent;
Routes;
Controllers;
Blade;
CRUD;
autenticación;
validación;
manejo de archivos;
seguridad;
integración de servicios externos.
La entrega final deberá incluir:
código fuente completo;
base de datos o migrations correspondientes;
sistema funcionando;
documentación breve del proyecto;
presentación del desarrollo;
demostración funcional;
defensa oral.

4. Metodología y estrategias de los trabajos
La asignatura se desarrollará mediante una metodología teórico-práctica basada en resolución de problemas y aprendizaje por proyectos.
Cada nuevo concepto será presentado inicialmente desde un problema concreto de desarrollo y posteriormente relacionado con su implementación dentro de Laravel.
Se priorizará la comprensión conceptual por sobre la memorización de sintaxis.
Por ejemplo, antes de introducir un Resource Controller, los estudiantes deberán comprender qué acciones necesita un CRUD; antes de utilizar Eloquent, deberán comprender qué representa una tabla y una relación; y antes de utilizar MVC deberán identificar qué responsabilidad corresponde a cada componente.
La metodología combinará:
exposiciones conceptuales breves;
ejemplos guiados;
análisis de código;
programación en vivo;
ejercicios progresivos;
resolución colectiva de problemas;
comparación entre PHP tradicional y Laravel;
análisis de errores frecuentes;
ejercicios de modelado de datos;
diseño de flujos MVC;
prácticas de CRUD;
desarrollo incremental del Proyecto Integrador;
correcciones en clase;
seguimiento individual de los proyectos.
Se utilizará recurrentemente una estrategia de traducción conceptual, relacionando conocimientos previos con su equivalente dentro de Laravel.
Por ejemplo:
PHP tradicional → Laravel
guardar.php → Route + Controller array de producto → Model SQL manual → Eloquent include de HTML → Blade validación manual → Validation / Form Request sesión manual → Auth / Middleware
De esta manera se buscará que el estudiante comprenda que el framework formaliza y organiza conocimientos previamente adquiridos.
La complejidad será incremental:
PHP + MySQL → POO → MVC → Laravel → CRUD → Auth → E-commerce → APIs
Asimismo, se prestará especial atención a criterios de calidad profesional:
claridad del código;
nombres significativos;
separación de responsabilidades;
estructura de carpetas;
correcta utilización de MVC;
reutilización;
validación;
seguridad;
coherencia del modelo de datos;
mantenibilidad general de la aplicación.

5. Bibliografía
Bibliografía básica
Material teórico elaborado por la cátedra.
Presentaciones y ejemplos desarrollados durante las clases.
Guías prácticas elaboradas para cada unidad.
Documentación oficial de Laravel.
Documentación oficial de PHP.
Documentación oficial de MySQL.
Documentación de Composer.
Documentación de Blade.
Documentación de Eloquent ORM.
Bibliografía y documentación técnica específica
Laravel Documentation: Routing.
Laravel Documentation: Controllers.
Laravel Documentation: Blade Templates.
Laravel Documentation: Validation.
Laravel Documentation: Eloquent ORM.
Laravel Documentation: Eloquent Relationships.
Laravel Documentation: Migrations.
Laravel Documentation: Authentication.
Laravel Documentation: Middleware.
Laravel Documentation: File Storage.
Laravel Documentation: Service Container.
Laravel Documentation: Service Providers.
Recursos complementarios
Material sobre Programación Orientada a Objetos en PHP.
Bibliografía y recursos sobre arquitectura MVC.
Recursos vinculados a diseño de bases de datos relacionales.
Documentación oficial de Mercado Pago.
Documentación oficial de PayPal.
Documentación de APIs de servicios logísticos utilizadas durante el curso.
Recursos técnicos seleccionados por la cátedra según las necesidades de cada proyecto.

6. Sistema de evaluación y aprobación de la materia
La evaluación será continua, progresiva e integradora, considerando tanto la comprensión conceptual como la capacidad para aplicar los contenidos en situaciones concretas de desarrollo.
Se evaluará especialmente:
comprensión de Programación Orientada a Objetos;
comprensión y aplicación del patrón MVC;
correcta definición de responsabilidades entre Routes, Controllers, Models y Views;
organización del código;
diseño de bases de datos;
correcta utilización de migrations;
utilización adecuada de Eloquent;
definición de relaciones entre modelos;
construcción de CRUD completos;
validación de datos;
manejo de formularios;
implementación de autenticación;
uso de Middleware;
manejo de archivos;
seguridad básica;
funcionamiento general del sistema;
calidad y coherencia del Proyecto Integrador;
capacidad para justificar las decisiones técnicas tomadas.
Instrumentos de evaluación
ejercicios prácticos desarrollados durante las clases;
trabajos prácticos individuales;
actividades de aplicación conceptual;
resolución de problemas;
entregas parciales del Proyecto Integrador;
seguimiento del desarrollo;
correcciones individuales y grupales;
presentación del Proyecto Integrador;
demostración funcional;
defensa oral;
documentación técnica del proyecto.
Régimen de aprobación
De acuerdo con el programa institucional de referencia, la cursada contempla instancias evaluativas parciales y el cumplimiento de los requisitos académicos establecidos por la institución, incluyendo asistencia y aprobación de las evaluaciones correspondientes. El antecedente de la asignatura establece una calificación mínima de 4 (cuatro) para las instancias parciales y un 75 % de asistencia para acceder a la instancia final.
Dado el carácter práctico y profesionalizante de la materia, se propone que el Proyecto Integrador constituya el núcleo principal de la evaluación final, ya que permite verificar de manera conjunta la comprensión conceptual, la capacidad de implementación y la integración de los contenidos desarrollados durante el cuatrimestre.
La aprobación de la instancia final deberá contemplar:
funcionamiento efectivo de la aplicación;
cumplimiento de los requerimientos solicitados;
correcta arquitectura del proyecto;
coherencia del modelo de datos;
aplicación de MVC;
calidad del código;
capacidad para explicar y defender el desarrollo realizado.













