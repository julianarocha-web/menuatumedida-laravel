# Clase_1_Laravel_PHP_MySQL_POO_Nueva_Escuela

Total: 29 diapositivas

## Diapositiva 1

Laravel · Clase 1
Repaso PHP + MySQL
e introducción a POO
{ }
DB
Proyecto integrador: e-commerce 100% funcional
MVC
Nueva Escuela de Diseño y Comunicación
‹#›

## Diapositiva 2

Laravel · Clase 1
La clase de hoy en una frase
PHP
Lógica
MySQL
Datos
Laravel no reemplaza PHP ni MySQL: los organiza.
Para construir un e-commerce real vamos a necesitar entender qué problema resuelve cada capa: lógica, datos, vistas, validaciones y seguridad.
OOP
Diseño
Idea rectora: aprender Laravel es aprender a diseñar aplicaciones web mantenibles, no solo a “hacer pantallas”.
Laravel
Framework
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 3

Laravel · Clase 1
Objetivos de hoy
1
Reconocer la estructura básica de un script PHP y su rol en una aplicación web.
2
Repasar operaciones esenciales de MySQL para modelar productos, usuarios y pedidos.
3
Entender qué es la Programación Orientada a Objetos y por qué Laravel la usa.
4
Conectar PHP + MySQL + OOP con el futuro patrón MVC de Laravel.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 4

Laravel · Clase 1
Plan de clase: 2 horas
Encuadre: e-commerce, stack y diagnóstico
0–10 min
Repaso PHP: sintaxis, funciones, formularios
10–35 min
Repaso MySQL: modelo relacional y CRUD
35–60 min
Actividad guiada: flujo producto → base de datos
60–80 min
Introducción a POO: clases, objetos, encapsulación
80–110 min
Cierre: MVC, ticket de salida y tarea
110–120 min
La clase alterna explicación, lectura de código y mini actividades. La prioridad no es memorizar sintaxis: es entender el flujo.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 5

Laravel · Clase 1
De la clase 1 al e-commerce funcional
1
Fundamentos
2
Laravel base
3
Datos
4
E-commerce
5
Integraciones
PHP · MySQL · OOP
Routes · Controllers · Blade
Migrations · Models · Eloquent
Carrito · Auth · Pedidos
Pagos · Envíos · Seguridad
El programa de la materia avanza hacia Laravel, MVC, CRUD, autenticación, manejo de archivos, APIs de pago/envío y seguridad.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 6

Laravel · Clase 1
Un e-commerce es un sistema de decisiones
• Catálogo: ¿qué productos existen y cómo se muestran?
• Stock y precios: ¿qué reglas determinan disponibilidad y valor?
• Usuarios: ¿quién compra, qué datos deja y cómo se autentica?
• Pedidos: ¿qué pasa desde el carrito hasta el pago?
• Administración: ¿quién puede crear, editar o eliminar información?
Catálogo
Carrito
Checkout
Usuarios
Productos
Pedidos
Pregunta profesional: ¿en qué tabla, clase o capa vive cada regla de negocio?
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 7

Laravel · Clase 1
Antes de Laravel: ¿qué ocurre en una petición web?
Navegador
cliente
Servidor
web
PHP
ejecuta lógica
MySQL
persistencia
HTML
respuesta
Laravel formaliza este flujo: recibe una ruta, llama a un controlador, consulta modelos y devuelve una vista o respuesta JSON.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 8

Laravel · Clase 1
Repaso PHP: rol dentro del stack
PHP es un lenguaje del lado del servidor.
PHP genera una respuesta
<?php
$nombre = "Producto demo";
$precio = 15000;
echo "<h1>$nombre</h1>";
echo "<p>Precio: $$precio</p>";
• Recibe datos de formularios y URLs.
• Ejecuta reglas de negocio.
• Se comunica con la base de datos.
• Genera HTML o respuestas JSON.
• En Laravel, queda organizado en clases, rutas, controladores y modelos.
Clave: PHP decide qué mostrar según datos, reglas y contexto.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 9

Laravel · Clase 1
PHP: sintaxis mínima que deben dominar
Variables + condicionales
• Las variables empiezan con $.
• Cada instrucción termina con ;
• Los bloques se agrupan con llaves { }.
• Los tipos más comunes: string, int, float, bool, array.
• La lógica condicional define caminos de ejecución.
<?php
$producto = "Remera básica";
$precio = 18000;
$hayStock = true;
if ($hayStock && $precio > 0) {
echo "Disponible";
} else {
echo "Sin stock";
}
En un e-commerce, una condición nunca es decorativa: puede habilitar compra, mostrar un error o frenar una operación.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 10

Laravel · Clase 1
PHP: arrays y estructuras de datos
Array asociativo
Los arrays permiten representar datos antes de guardarlos en la base.
<?php
$producto = [
"id" => 7,
"nombre" => "Zapatilla urbana",
"precio" => 72000,
"stock" => 12
];
echo $producto["nombre"];
echo $producto["precio"];
• Array indexado: lista ordenada.
• Array asociativo: clave → valor.
• En Laravel veremos Collections, pero la idea base es la misma: agrupar datos para procesarlos.
Puente conceptual: array de producto → fila en tabla products → objeto Product.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 11

Laravel · Clase 1
PHP: funciones para no repetir lógica
Una función encapsula una operación con nombre.
Función con parámetros y retorno
<?php
function precioFinal(float $precio, float $iva): float {
return $precio * (1 + $iva);
}
$total = precioFinal(10000, 0.21);
echo $total;
• Mejora legibilidad.
• Evita copiar y pegar lógica.
• Permite testear partes pequeñas del sistema.
• Prepara el salto a métodos dentro de clases.
Pregunta: ¿qué otras reglas podrían ser funciones? Descuento, envío, impuestos, validación de stock.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 12

Laravel · Clase 1
Formularios: GET y POST
• GET suele exponer datos en la URL. Ejemplo: búsqueda, filtros, paginación.
• POST envía datos en el cuerpo de la petición. Ejemplo: registro, login, compra.
• En Laravel, estas diferencias aparecerán en rutas y controladores.
POST en PHP
GET
leer / filtrar
<form method="POST" action="guardar.php">
<input name="nombre">
<input name="precio">
<button>Guardar</button>
</form>
<?php
$nombre = $_POST["nombre"] ?? "";
POST
crear / enviar
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 13

Laravel · Clase 1
Validar no es opcional
Nunca asumimos que el usuario envía datos correctos.
Validación básica
<?php
$precio = $_POST["precio"] ?? null;
if (!is_numeric($precio) || $precio <= 0) {
$errores[] = "El precio debe ser mayor a 0";
}
if (empty($_POST["nombre"])) {
$errores[] = "El nombre es obligatorio";
}
• Campos obligatorios.
• Tipos y rangos: precio numérico, stock entero, email válido.
• Longitudes máximas.
• Reglas de negocio: stock mayor o igual a 0.
• Mensajes de error claros para el usuario.
Laravel traerá validadores formales, pero el criterio profesional empieza acá.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 14

Laravel · Clase 1
Repaso MySQL: datos persistentes
Una base de datos relacional guarda información estructurada en tablas.
users
id
name
email
orders
id
user_id
total
order_items
order_id
product_id
qty
• Tabla: conjunto de registros del mismo tipo.
• Fila: un registro. Ejemplo: un producto.
• Columna: atributo. Ejemplo: precio, stock, categoría.
• Clave primaria: identifica una fila.
• Clave foránea: relaciona tablas.
products
id
name
price
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 15

Laravel · Clase 1
Modelo mínimo de datos para el proyecto
products
id, name, price, stock, category_id
Pensar datos es pensar comportamiento futuro.
categories
id, name, slug
users
id, name, email, password
Lo que se modela mal al inicio se paga después en código complejo.
orders
id, user_id, status, total
order_items
id, order_id, product_id, quantity, price
Regla de diseño: una compra no es “un producto”. Una compra es un pedido con varios ítems.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 16

Laravel · Clase 1
CRUD en SQL: crear, leer, actualizar, borrar
Crear y leer
CRUD será el corazón del panel administrador del e-commerce.
-- CREATE
INSERT INTO products (name, price, stock)
VALUES ('Remera', 18000, 10);
-- READ
SELECT id, name, price
FROM products
WHERE stock > 0;
• Crear productos y categorías.
• Listar productos y pedidos.
• Editar precios, stock e imágenes.
• Eliminar o desactivar registros.
Actualizar y borrar
-- UPDATE
UPDATE products
SET price = 19500
WHERE id = 1;
-- DELETE
DELETE FROM products
WHERE id = 1;
En Laravel esto se traducirá en rutas RESTful, controladores, modelos y vistas.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 17

Laravel · Clase 1
Relaciones y JOIN: leer información completa
Un JOIN combina filas relacionadas.
Producto + categoría
SELECT products.name, categories.name AS category
FROM products
JOIN categories
ON products.category_id = categories.id
WHERE products.stock > 0;
• Evita duplicar información.
• Permite construir vistas ricas: “producto + categoría”.
• Laravel lo ocultará parcialmente con Eloquent, pero la relación sigue existiendo en la base.
products
category_id
categories
id
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 18

Laravel · Clase 1
PHP + MySQL: consultas seguras
No concatenamos datos del usuario dentro de SQL.
• Se separa la consulta de los datos.
• Reduce riesgo de SQL Injection.
• Mejora consistencia del código.
• Laravel usará Query Builder y Eloquent, que también protegen este flujo si se usan correctamente.
PDO + prepared statement
<?php
$stmt = $pdo->prepare(
"SELECT * FROM products WHERE id = ?"
);
$stmt->execute([$id]);
$producto = $stmt->fetch();
Seguridad no es una unidad aislada: aparece en cada formulario, consulta y acción del usuario.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 19

Laravel · Clase 1
Introducción a Programación Orientada a Objetos
POO es una forma de organizar el código alrededor de entidades con datos y comportamiento.
Product
name
price
stock
finalPrice()
hasStock()
• Un objeto representa algo del dominio: Producto, Usuario, Pedido, Carrito.
• Agrupa atributos y acciones relacionadas.
• Reduce código suelto y mejora mantenibilidad.
• Laravel está construido sobre clases y objetos.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 20

Laravel · Clase 1
Clase, objeto e instancia
Clase e instancias
Objeto A
Remera
class Product {
public string $name;
public float $price;
}
$remera = new Product();
$remera->name = "Remera";
$zapatilla = new Product();
$zapatilla->name = "Zapatilla";
Clase
Product
Objeto B
Zapatilla
La clase es el molde. El objeto es una instancia concreta creada a partir de ese molde.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 21

Laravel · Clase 1
Propiedades, métodos y constructor
PHP moderno
• Propiedades: datos del objeto.
• Métodos: acciones o cálculos del objeto.
• Constructor: estado inicial obligatorio.
• $this: referencia al objeto actual.
class Product {
public function __construct(
public string $name,
public float $price,
public int $stock
) {}
public function hasStock(): bool {
return $this->stock > 0;
}
}
$product = new Product("Remera", 18000, 10);
Regla práctica: si una función siempre opera sobre un producto, probablemente sea un método de Product.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 22

Laravel · Clase 1
Encapsulación: proteger reglas internas
Encapsular significa controlar cómo se modifica el estado de un objeto.
• Evita cambios arbitrarios.
• Hace explícitas las reglas de negocio.
• Facilita depuración y mantenimiento.
• Es clave para pedidos, stock y pagos.
Regla dentro del objeto
class Product {
private int $stock;
public function decreaseStock(int $qty): void {
if ($qty <= 0 || $qty > $this->stock) {
throw new Exception("Stock inválido");
}
$this->stock -= $qty;
}
}
No debería ser posible dejar stock negativo por accidente.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 23

Laravel · Clase 1
Herencia, composición e interfaces: primera intuición
• Herencia: una clase especializa otra. Ejemplo: DigitalProduct podría extender Product.
• Composición: un objeto usa otros objetos. Ejemplo: Order tiene muchos OrderItems.
• Interface: contrato de métodos. Ejemplo: PaymentGateway debe poder cobrar.
Order
OrderItem
Product
Contrato
interface PaymentGateway {
public function charge(Order $order): PaymentResult;
}
Para este curso, lo primero es dominar clases y objetos. Interfaces y patrones aparecerán gradualmente.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 24

Laravel · Clase 1
Ejemplo integrado: producto en un e-commerce
Product.php
¿Qué reglas concentra esta clase?
class Product {
public function __construct(
public string $name,
public float $price,
private int $stock
) {}
public function canBePurchased(int $qty): bool {
return $qty > 0 && $qty <= $this->stock;
}
public function subtotal(int $qty): float {
return $this->price * $qty;
}
}
• Disponibilidad de compra.
• Cálculo de subtotal.
• Estado mínimo del producto.
• Comportamiento reutilizable por carrito, checkout y panel admin.
El objetivo de POO no es escribir más código: es ubicar mejor las responsabilidades.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 25

Laravel · Clase 1
Puente a Laravel: MVC
Ruta
URL + método
Controller
coordina
Model
datos/reglas
View
HTML/Blade
MVC separa responsabilidades para que la aplicación pueda crecer sin volverse caótica.
Hoy vimos los cimientos. En Laravel, esos cimientos tendrán ubicación concreta dentro del framework.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 26

Laravel · Clase 1
Traducción conceptual a Laravel
PHP suelto
Laravel
guardar.php
Route + ProductController@store
array de producto
Model Product / Eloquent
SQL manual
Query Builder / Eloquent ORM
include de HTML
Blade template
validación manual
Request validation
sesión/login manual
Auth / Middleware
Aprender Laravel será aprender un mapa de responsabilidades.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 27

Laravel · Clase 1
Chequeo de comprensión
Responder oralmente o por escrito en 5 minutos:
1
¿Qué diferencia hay entre una variable, un array y un objeto?
2
¿Por qué un producto debería tener una clave primaria en la base de datos?
3
¿Qué validaciones mínimas harías antes de guardar un producto?
4
¿Qué responsabilidad tendría un Controller y cuál un Model?
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 28

Laravel · Clase 1
Tarea para la próxima clase
• Elegir una categoría de e-commerce para trabajar durante la cursada.
• Listar 8 campos posibles para la tabla products.
• Proponer 3 tablas relacionadas y justificar sus relaciones.
• Escribir una clase Product simple con constructor, propiedades y un método hasStock().
• Traer dudas concretas sobre PHP, MySQL u OOP.
Modelo esperado
class Product {
public function __construct(
public string $name,
public float $price,
public int $stock
) {}
public function hasStock(): bool {
return $this->stock > 0;
}
}
La próxima clase empieza conectando esta clase Product con una ruta, un controlador y una vista.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 29

Laravel · Clase 1
Cierre
Hoy construimos el puente:
PHP + MySQL + POO → Laravel → e-commerce funcional
• Repasamos lógica del servidor, formularios, validación y CRUD.
• Modelamos datos mínimos de un e-commerce.
• Introdujimos clases, objetos, métodos y encapsulación.
• Anticipamos MVC como forma de organizar responsabilidades.
Base curricular tomada del programa de Portales y Comercio Electrónico / Laravel de Nueva Escuela.
‹#›
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
