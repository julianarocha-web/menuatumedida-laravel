# Clase_2_Laravel_SQL_CRUD_POO_Nueva_Escuela

Total: 34 diapositivas

## Diapositiva 1

Laravel · Clase 2
{ }
SQL CRUD + POO aplicada
al e-commerce
DB
Continuación desde el modelo mínimo de datos: products, categories, users, orders y order_items.
MVC
CRUD
JOIN
PDO
Product
Cart
MVC
1
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 2

Laravel · Clase 2
Dónde quedamos y qué retomamos
La clase pasada llegamos al modelo mínimo de datos del proyecto.
products
id · name · price · stock · category_id
Ahora vamos a responder dos preguntas
categories
id · name · slug
users
id · name · email · password
¿Cómo creo, leo, actualizo y borro esos datos?
¿Qué objetos del sistema contienen esas reglas?
orders
id · user_id · status · total
order_items
id · order_id · product_id · quantity · price
2
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 3

Laravel · Clase 2
Objetivos de hoy
De SQL operativo a pensamiento orientado a objetos.
Ejecutar mentalmente un CRUD SQL
Conectar seguridad con consultas
1
3
Crear, leer, actualizar y eliminar registros vinculados al e-commerce.
Entender por qué no concatenamos datos del usuario en SQL.
Leer relaciones con JOIN
Diseñar clases simples en PHP
2
4
Comprender cómo se arma una vista con datos de varias tablas.
Product, Cart, Order y sus responsabilidades mínimas.
3
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 4

Laravel · Clase 2
Plan de clase: 2 horas
Explicación breve, lectura de código y práctica guiada.
Recuperación: modelo de datos
0-10
Criterio de avance
CRUD en SQL: CREATE, READ, UPDATE, DELETE
10-40
No buscamos memorizar comandos: buscamos entender responsabilidades.
Relaciones, JOIN y pedidos
40-60
Consultas seguras y validación
60-75
POO: clases, objetos, métodos y encapsulación
75-100
Mini práctica: Product, Cart y Order
100-115
Ticket de salida y tarea
115-120
4
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 5

Laravel · Clase 2
Calentamiento: de tabla a pantalla
Un e-commerce traduce datos en acciones visibles para el usuario.
Tabla
products
Consulta
SQL
Regla
de negocio
Pantalla
admin
si el admin cambia el precio de un producto,
¿qué tabla se modifica, qué validación se aplica y qué parte del sistema debería decidirlo?
5
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 6

Bloque 1
CRUD en SQL
El panel administrador del e-commerce se construye sobre estas cuatro operaciones.
CREATE
READ
UPDATE
DELETE
6
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 7

Laravel · Clase 2
CRUD: administrar bases de datos
CRUD es el patrón operativo básico para administrar datos persistentes.
Qué significa
Dónde aparece en el proyecto
Create: insertar un nuevo registro.
ABM de productos y categorías.
Read: consultar uno o varios registros.
Listado de pedidos y detalle de compra.
Update: modificar un registro existente.
Edición de precios, stock e imágenes.
Delete: eliminar o desactivar un registro.
Gestión de usuarios y permisos.
7
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 8

Laravel · Clase 2
CREATE: insertar datos correctamente
Crear un producto implica datos mínimos, validación y consistencia.
SQL
Antes de insertar
-- Crear una categoría
INSERT INTO categories (name, slug)
VALUES ('Indumentaria', 'indumentaria');
-- Crear un producto
INSERT INTO products (name, price, stock, category_id)
VALUES ('Remera básica', 18000, 10, 1);
name no vacío.
price numérico y mayor a 0.
stock entero y mayor o igual a 0.
category_id debe existir.
slug único para URLs limpias.
8
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 9

Laravel · Clase 2
READ: leer no es solo hacer SELECT *
Un listado útil filtra, ordena y devuelve solo lo necesario.
SQL
Criterios de lectura
-- Productos disponibles para el catálogo
SELECT id, name, price, stock
FROM products
WHERE stock > 0
ORDER BY name ASC;
-- Búsqueda simple por nombre
SELECT id, name, price
FROM products
WHERE name LIKE '%remera%';
Qué columnas necesita la pantalla.
Qué filtros pidió el usuario.
Qué orden facilita la navegación.
Cuántos registros se muestran por página.
Laravel más adelante: Query Builder, Eloquent, pagination.
9
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 10

Laravel · Clase 2
UPDATE y DELETE: modificar con cuidado
Una operación mal filtrada puede afectar toda una tabla.
SQL
Reglas de seguridad operativa
-- Actualizar precio y stock
UPDATE products
SET price = 19500, stock = 8
WHERE id = 1;
-- Borrar físicamente un registro
DELETE FROM products
WHERE id = 1;
Nunca actualizar sin WHERE.
Validar permisos del usuario.
Preferir desactivar antes que borrar si hay historial.
No borrar productos asociados a pedidos sin revisar relaciones.
En e-commerce, el historial de compra vale más que la limpieza de la tabla.
10
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 11

Laravel · Clase 2
JOIN: leer información completa
Las pantallas reales suelen necesitar datos de más de una tabla.
SQL
SELECT products.name, products.price,
categories.name AS category
FROM products
JOIN categories
ON products.category_id = categories.id
WHERE products.stock > 0;
products
category_id
categories
id
Definición: un JOIN combina filas relacionadas a partir de una condición lógica entre columnas.
11
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 12

Laravel · Clase 2
Pedidos: una compra no es una sola fila
El pedido separa el encabezado de sus ítems para soportar múltiples productos.
users
id
orders
user_id
order_items
order_id · product_id
products
id
Regla de diseño: guardamos el precio del ítem comprado para conservar el historial aunque el precio actual del producto cambie.
12
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 13

Laravel · Clase 2
Consultas seguras: datos del usuario no son SQL
El usuario escribe valores; la aplicación construye una consulta segura.
PHP + PDO
Prepared statement
// Riesgoso: concatena entrada del usuario
$sql = "SELECT * FROM products WHERE id = $id";
// Mejor: consulta preparada con PDO
$stmt = $pdo->prepare(
"SELECT * FROM products WHERE id = ?"
);
$stmt->execute([$id]);
$product = $stmt->fetch();
Separa consulta y valores.
Reduce riesgo de SQL Injection.
Vuelve el código más predecible.
Anticipa Query Builder y Eloquent en Laravel.
13
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 14

Laravel · Clase 2
Mini práctica 1: diseñar el CRUD del admin
Trabajo rápido en parejas o grupos de 3.
Consigna: para una pantalla de administración de productos, completen esta matriz.
Acción
SQL base
Validación mínima
Pantalla
Crear
Listar
Editar
Borrar/desactivar
Tiempo sugerido: 8 minutos + 5 minutos de puesta en común.
14
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 15

Bloque 2
Programación Orientada a Objetos
Pasamos de operaciones sueltas a responsabilidades ubicadas en entidades del dominio.
Product
Cart
Order
User
15
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 16

Laravel · Clase 2
POO: definición útil para este curso
POO organiza el código alrededor de entidades con datos y comportamiento.
Sin POO
Con POO
Funciones sueltas.
Objetos del dominio.
Arrays sin reglas claras.
Datos + comportamiento juntos.
Mucho código repetido.
Reglas centralizadas.
Difícil ubicar responsabilidades.
Código más mantenible y testeable.
16
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 17

Laravel · Clase 2
Clase, objeto e instancia
La clase es el molde; el objeto es una instancia concreta.
PHP
Objeto A
Remera
class Product {
public string $name;
public float $price;
}
$remera = new Product();
$remera->name = 'Remera';
$zapatilla = new Product();
$zapatilla->name = 'Zapatilla';
Clase
Product
Objeto B
Zapatilla
17
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 18

Laravel · Clase 2
Propiedades, métodos y $this
Un método es una función que vive dentro de una clase.
PHP
$this
class Product {
public string $name;
public float $price;
public int $stock;
public function hasStock(): bool {
return $this->stock > 0;
}
}
$product = new Product();
$product->stock = 10;
var_dump($product->hasStock());
Referencia al objeto actual.
Permite acceder a propiedades.
Evita pasar todos los datos como parámetros.
Hace que el objeto pueda responder por su propio estado.
18
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 19

Laravel · Clase 2
Constructor: estado inicial obligatorio
El constructor evita objetos incompletos o inconsistentes desde el inicio.
PHP moderno
¿Por qué importa?
class Product {
public function __construct(
public string $name,
public float $price,
public int $stock
) {}
}
$product = new Product(
'Remera',
18000,
10
);
Obliga a crear productos con nombre, precio y stock.
Hace explícitos los tipos esperados.
Reduce errores de propiedades sin inicializar.
Prepara modelos con reglas más claras.
19
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 20

Laravel · Clase 2
Encapsulación: proteger reglas internas
No todo dato debe poder modificarse desde cualquier lugar.
PHP
Definición
class Product {
private int $stock;
public function decreaseStock(int $qty): void {
if ($qty <= 0 || $qty > $this->stock) {
throw new Exception('Stock inválido');
}
$this->stock -= $qty;
}
}
Encapsular significa controlar cómo se lee o modifica el estado interno de un objeto.
private protege el stock.
decreaseStock concentra la regla.
No debería existir stock negativo por accidente.
20
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 21

Laravel · Clase 2
Ejemplo integrado: Product
Una clase útil concentra comportamiento que el sistema reutiliza.
Product.php
Responsabilidades
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
Conocer su nombre y precio.
Saber si una cantidad se puede comprar.
Calcular subtotal.
No exponer libremente el stock interno.
Regla: si una función siempre opera sobre un producto, probablemente sea un método de Product.
21
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 22

Laravel · Clase 2
Del Product al Cart
El carrito no es una tabla todavía: es una regla de interacción.
Product
precio · stock
CartItem
producto · cantidad
Cart
ítems · total
PHP - idea simplificada
class Cart {
private array $items = [];
public function add(Product $product, int $qty): void {
if (!$product->canBePurchased($qty)) {
throw new Exception('Cantidad inválida');
}
$this->items[] = [$product, $qty];
}
}
22
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 23

Laravel · Clase 2
Order y OrderItem: composición
Un pedido se compone de ítems; cada ítem conserva la foto de la compra.
OrderItem.php
Composición
class OrderItem {
public function __construct(
public int $productId,
public string $productName,
public float $unitPrice,
public int $quantity
) {}
public function subtotal(): float {
return $this->unitPrice * $this->quantity;
}
}
Un objeto complejo se construye usando otros objetos más simples.
Order
OrderItem
Order tiene muchos OrderItems.
23
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 24

Laravel · Clase 2
Herencia, composición e interfaces
Por ahora buscamos intuición, no profundidad excesiva.
Herencia
Composición
Interface
Una clase especializa otra.
Ej.: DigitalProduct podría extender Product.
Un objeto usa otros objetos.
Ej.: Order tiene OrderItems.
Un contrato de métodos.
Ej.: todo gateway debe cobrar.
Regla inicial para este curso: preferimos clases simples y responsabilidades claras antes que jerarquías complejas.
24
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 25

Laravel · Clase 2
Interface: contrato para integrar pagos
El e-commerce puede cambiar de proveedor sin cambiar toda la app.
PHP
interface PaymentGateway {
public function charge(Order $order): PaymentResult;
}
class MercadoPagoGateway implements PaymentGateway {
public function charge(Order $order): PaymentResult {
// llamada a API de MercadoPago
}
}
class PaypalGateway implements PaymentGateway {
public function charge(Order $order): PaymentResult {
// llamada a API de PayPal
}
}
PaymentGateway
charge(order)
MercadoPago
PayPal
La app depende del contrato, no del proveedor concreto.
25
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 26

Laravel · Clase 2
Mapa de responsabilidades del e-commerce
Diseñar bien es decidir quién sabe y quién decide.
Product
Cart
Order
precio, stock, disponibilidad
ítems temporales, total estimado
compra confirmada, estado, total final
User
PaymentGateway
ShippingService
identidad, email, permisos
cobrar y devolver resultado
calcular envío y seguimiento
26
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 27

Bloque 3
Puente a Laravel
Todo lo anterior tendrá un lugar específico dentro del framework.
Route
Controller
Model
View
27
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 28

Laravel · Clase 2
MVC: separar responsabilidades
Laravel formaliza el flujo de una petición web.
Route
URL + método
Controller
coordina
Model
datos + reglas
View
Blade HTML
MVC no es teoría decorativa: es una forma de evitar que consultas, HTML y reglas de negocio terminen mezcladas en un solo archivo.
28
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 29

Laravel · Clase 2
Traducción conceptual a Laravel
Lo que hoy parece suelto, Laravel lo ubica en carpetas y clases.
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
La próxima clase empieza con routes, controllers y Blade sobre un caso de productos.
29
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 30

Laravel · Clase 2
Mini práctica 2: diseñar clases del proyecto
Consigna individual o grupal para cerrar el bloque técnico.
Elegí un e-commerce posible y definí:
3 propiedades de Product
1 regla de stock o precio
1
3
2 métodos de Product
Qué dato vive en orders y qué dato vive en order_items
2
4
Entrega oral: cada grupo comparte una decisión de diseño y la justifica.
30
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 31

Laravel · Clase 2
Errores frecuentes que vamos a evitar
Estos problemas aparecen temprano y después escalan.
En SQL
En POO
Usar una sola tabla para todo.
Crear clases sin comportamiento.
No definir claves primarias o foráneas.
Usar todo public sin criterio.
Borrar registros con historial asociado.
Repetir reglas en varios archivos.
Concatenar input del usuario en consultas.
Poner HTML, SQL y lógica juntos.
31
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 32

Laravel · Clase 2
Ticket de salida
Responder en 5 minutos antes de cerrar.
1. ¿Qué diferencia hay entre products y order_items?
2. ¿Por qué un UPDATE debe tener WHERE?
3. ¿Qué responsabilidad debería tener Product y cuál Cart?
4. ¿Qué parte de Laravel se parece a guardar.php?
32
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 33

Laravel · Clase 2
Tarea para la próxima clase
La próxima clase entramos en Laravel: rutas, controladores y vistas.
Modelo esperado
Entregar / traer
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
Definir temática del e-commerce.
Listar 8 campos posibles de products.
Proponer 3 tablas relacionadas.
Escribir una clase Product con constructor y método hasStock().
33
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 34

Laravel · Clase 2
Hoy conectamos:
Route
MySQL CRUD + POO -> MVC -> Laravel
Controller
Repasamos operaciones SQL esenciales.
Model
Vimos relaciones, JOIN y consultas seguras.
Diseñamos clases con datos y comportamiento.
Dejamos listo el puente hacia Laravel base.
34
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
