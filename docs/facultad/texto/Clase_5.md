# Clase_5_Laravel_Eloquent_Migrations_Relaciones_Nueva_Escuela

Total: 39 diapositivas

## Diapositiva 1

Laravel · Clase 5
{ }
Eloquent, migrations
y relaciones
DB
Del MVC entendido al modelo de datos implementado en Laravel
MVC
Eloquent
Migrations
Models
Relaciones
CRUD
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
1

## Diapositiva 2

Laravel · Clase 5
Dónde retomamos
La clase anterior llegó hasta el Bloque 3: Blade, formularios, CSRF y errores de validación.
Ya vimos
Hoy profundizamos
Para qué
Route → Controller → View
create/store · edit/update
Formularios Blade · @csrf
Model → Eloquent → Migrations
Relaciones entre entidades
Consultas reutilizables
Que el CRUD deje de ser una pantalla
y pase a ser una aplicación con datos
consistentes.
Si el Controller coordina,
entonces el Model tiene que representar datos, relaciones y reglas del dominio.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
2

## Diapositiva 3

Laravel · Clase 5
Objetivos de la clase
Al terminar, deberían poder crear la base de un catálogo usando Laravel.
Comprender migrations
Configurar Models
1
2
Definir tablas como código versionable: products, categories y claves foráneas.
Entender Product y Category como clases que representan tablas y registros.
Leer y escribir con Eloquent
Modelar relaciones
3
4
Usar create, where, orderBy, paginate, findOrFail y update sin SQL manual.
Aplicar belongsTo y hasMany al catálogo y anticipar orders/order_items.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
3

## Diapositiva 4

Laravel · Clase 5
{ }
Bloque 1
Del formulario al Model
DB
Request
Controller
Model
DB
View
MVC
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
5

## Diapositiva 5

Laravel · Clase 5
Recuperación: ¿qué pasa después del formulario?
Un formulario no guarda por sí solo: inicia una petición que debe validarse y persistirse.
Blade
Request
Controller
Model
DB
formulario
datos
coordina
persistencia
tabla
Pregunta para abrir
Respuesta esperada
Si el admin carga nombre, precio, stock y categoría, ¿qué parte valida y qué parte crea la fila en la base?
El Controller valida y coordina. El Model/Eloquent crea o actualiza el registro en la tabla correspondiente.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
6

## Diapositiva 6

Laravel · Clase 5
El problema: SQL disperso en toda la aplicación
Antes de Laravel, una funcionalidad podía mezclar SQL, HTML y reglas en el mismo archivo.
¿Qué problemas aparecen?
PHP suelto
• La consulta depende de strings repetidos.
• La seguridad queda expuesta si se concatena input.
• La responsabilidad no está clara.
• Cuesta reutilizar reglas como stock disponible.
$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);
if ($product['stock'] > 0) {
echo "Disponible";
}
Laravel propone Models + Eloquent para que la lógica de datos tenga una ubicación clara.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
7

## Diapositiva 7

Laravel · Clase 5
ORM: una definición útil
Eloquent es el ORM de Laravel. ORM significa Object Relational Mapper.
Tabla
Model
Objeto
Controller
View
products
Product
$product
usa datos
muestra
Definición para clase
Un ORM permite trabajar con registros de una base de datos como objetos del lenguaje de programación. En Laravel, Eloquent conecta una clase PHP con una tabla relacional.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
8

## Diapositiva 8

Laravel · Clase 5
Model: la clase que representa una entidad
Un Model no es una pantalla: representa datos, relaciones y comportamiento del dominio.
Product
Category
Order
Tabla: products
Entidad: producto
Reglas: stock, precio, categoría
Tabla: categories
Entidad: categoría
Relación: muchos productos
Tabla: orders
Entidad: pedido
Relación: muchos ítems
Regla práctica
app/Models/Product.php
• El Model no debería generar HTML.
• El Model no decide qué vista mostrar.
• El Model sí puede expresar relaciones y consultas frecuentes.
class Product extends Model
{
// representa products
}
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
9

## Diapositiva 9

Laravel · Clase 5
Primer puente: SQL manual vs Eloquent
La misma intención puede expresarse como consulta SQL o como operación sobre el Model.
SQL
Eloquent
SELECT id, name, price, stock
FROM products
WHERE stock > 0
ORDER BY name ASC;
$products = Product::where('stock', '>', 0)
->orderBy('name')
->get();
La idea es la misma: buscar productos disponibles y ordenarlos. Cambia la forma de escribirlo y la ubicación dentro del proyecto.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
10

## Diapositiva 10

Laravel · Clase 5
Mini chequeo: ¿qué responsabilidad tiene cada parte?
Antes de avanzar, reforzamos MVC con Eloquent adentro del recorrido.
Situación
Capa más esperada
Justificación
El admin envía un formulario
Controller / Request
Recibe y valida entrada externa
Crear fila en products
Model / Eloquent
Representa la tabla y persiste datos
Mostrar errores debajo del input
View / Blade
Presentación y feedback visual
Decidir redirigir al listado
Controller
Coordina la respuesta
Definir category()
Model
Relación del dominio
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
11

## Diapositiva 11

Laravel · Clase 5
{ }
Bloque 2
Migrations: base de datos como código
DB
Schema
table
foreignId
migrate
MVC
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
12

## Diapositiva 12

Laravel · Clase 5
¿Qué es una migration?
Una migration describe cambios en la estructura de la base de datos usando código PHP.
Sin migrations
Con migrations
Para el e-commerce
Cada alumno o desarrollador crea tablas manualmente en phpMyAdmin. Es difícil saber qué cambió y en qué orden.
El proyecto guarda la estructura de la base como archivos versionables. Todos pueden reconstruir el esquema.
products, categories, orders y order_items se crean de forma consistente y repetible.
Definición simple: una migration es el historial técnico de cómo evoluciona la estructura de la base de datos.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
13

## Diapositiva 13

Laravel · Clase 5
Comandos iniciales de Artisan
Artisan permite crear archivos y ejecutar tareas frecuentes desde consola.
Lectura del comando
Terminal
• make:model crea la clase Product.
• -m agrega una migration.
• -c agrega un Controller.
• -r lo prepara como Resource Controller.
• migrate aplica cambios a la base.
# Crear Model + migration + controller resource
php artisan make:model Product -mcr
# Ejecutar migrations pendientes
php artisan migrate
# Volver atrás la última tanda
php artisan migrate:rollback
Cuidado
Primero entender qué archivo necesitamos. Después usar Artisan para generarlo más rápido.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
14

## Diapositiva 14

Laravel · Clase 5
Migration de categories
Antes de products conviene tener categorías para poder relacionarlas.
Qué representa cada campo
database/migrations/...create_categories_table.php
• id identifica cada categoría.
• name es el nombre visible.
• slug puede usarse en URLs limpias.
• active permite publicar u ocultar.
• timestamps guarda created_at y updated_at.
Schema::create('categories', function (Blueprint $table) {
$table->id();
$table->string('name');
$table->string('slug')->unique();
$table->boolean('active')->default(true);
$table->timestamps();
});
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
15

## Diapositiva 15

Laravel · Clase 5
Migration de products
La tabla products concentra el catálogo administrable del e-commerce.
Decisiones de diseño
create_products_table.php
• category_id relaciona producto con categoría.
• decimal evita problemas con precios.
• stock no debería ser negativo.
• image guarda ruta, no el archivo completo.
• active permite desactivar sin destruir historial.
Schema::create('products', function (Blueprint $table) {
$table->id();
$table->foreignId('category_id')
->constrained()
->cascadeOnUpdate();
$table->string('name');
$table->text('description')->nullable();
$table->decimal('price', 10, 2);
$table->integer('stock')->default(0);
$table->string('image')->nullable();
$table->boolean('active')->default(true);
$table->timestamps();
});
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
16

## Diapositiva 16

Laravel · Clase 5
Claves foráneas: consistencia entre tablas
Una clave foránea evita relaciones imposibles entre datos.
Laravel Schema
Qué garantiza
$table->foreignId('category_id')
->constrained()
->cascadeOnUpdate();
No debería existir un producto apuntando a una categoría inexistente.
Ejemplo
Si producto.category_id = 3, entonces debe existir categories.id = 3.
categories
products
id, name
category_id
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
17

## Diapositiva 17

Laravel · Clase 5
Rollback, migrate:fresh y cuidado operativo
Durante aprendizaje podemos reconstruir la base, pero en producción eso sería peligroso.
Lectura profesional
Terminal
• rollback sirve para corregir una migration reciente.
• fresh borra tablas y datos.
• fresh --seed es útil para empezar pruebas limpias.
• En un e-commerce real no se borra producción por comodidad.
# Volver atrás la última migration
php artisan migrate:rollback
# Borrar todo y recrear desde cero
php artisan migrate:fresh
# Recrear y cargar datos de prueba
php artisan migrate:fresh --seed
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
18

## Diapositiva 18

Laravel · Clase 5
Seeder: datos mínimos para probar
No podemos probar catálogo, listado o detalle si la base está vacía.
Por qué usarlo
DatabaseSeeder.php
• Permite poblar la base con datos repetibles.
• Evita cargar todo manualmente en cada prueba.
• Hace visible si Models, migrations y relaciones funcionan.
Category::create([
'name' => 'Indumentaria',
'slug' => 'indumentaria',
]);
Product::create([
'category_id' => 1,
'name' => 'Remera básica',
'price' => 18000,
'stock' => 10,
'active' => true,
]);
Prueba concreta
Después del seed debería poder verse al menos una categoría y un producto.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
19

## Diapositiva 19

Laravel · Clase 5
{ }
Bloque 3
Consultas Eloquent para CRUD
DB
get
paginate
findOrFail
create
update
MVC
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
20

## Diapositiva 20

Laravel · Clase 5
Leer datos: get() y paginate()
El listado del admin y el catálogo público no siempre consultan lo mismo.
Idea clave
ProductController@index
• get() trae todos los resultados de la consulta.
• paginate() divide resultados en páginas.
• El catálogo público puede ocultar inactivos o sin stock.
• El admin puede necesitar ver todo para gestionar.
// Catálogo público
$products = Product::where('active', true)
->where('stock', '>', 0)
->orderBy('name')
->get();
// Panel administrador
$products = Product::orderBy('created_at', 'desc')
->paginate(10);
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
21

## Diapositiva 21

Laravel · Clase 5
find(), findOrFail() y Route Model Binding
Para trabajar con un registro puntual necesitamos ubicarlo de forma segura.
Eloquent
Diferencia central
// Buscar por id y recibir null si no existe
$product = Product::find($id);
// Buscar por id o lanzar 404
$product = Product::findOrFail($id);
// Route Model Binding
public function show(Product $product)
{
return view('products.show', compact('product'));
}
find() puede devolver null. findOrFail() corta con error 404 si no existe.
Por qué importa
Si el usuario entra a /productos/999, la app debe responder correctamente y no romperse.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
22

## Diapositiva 22

Laravel · Clase 5
Crear datos: validate() + Product::create()
store() recibe datos externos, valida y recién después persiste.
Lectura paso a paso
ProductController@store
• Request trae lo que envió el formulario.
• validate() funciona como frontera de seguridad.
• Product::create() necesita $fillable.
• redirect evita duplicar envíos al refrescar.
public function store(Request $request)
{
$data = $request->validate([
'name' => ['required', 'max:255'],
'price' => ['required', 'numeric', 'min:1'],
'stock' => ['required', 'integer', 'min:0'],
'category_id' => ['required', 'exists:categories,id'],
]);
Product::create($data);
return redirect()->route('admin.products.index');
}
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
23

## Diapositiva 23

Laravel · Clase 5
$fillable: controlar mass assignment
Mass assignment permite crear con arrays, pero requiere definir qué campos aceptamos.
Idea central
app/Models/Product.php
• $fillable no es decoración: limita qué columnas pueden llenarse masivamente.
• Evita que datos no deseados modifiquen columnas sensibles.
• Debe coincidir con los campos que el formulario realmente puede administrar.
class Product extends Model
{
protected $fillable = [
'name',
'description',
'price',
'stock',
'category_id',
'image',
'active',
];
}
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
24

## Diapositiva 24

Laravel · Clase 5
Actualizar datos: update()
update() aplica cambios sobre un registro existente.
Diferencia con store()
ProductController@update
• Route Model Binding entrega el producto existente.
• Se validan los datos nuevos.
• update() modifica ese registro, no crea otro.
• Debe diferenciarse de store().
public function update(Request $request, Product $product)
{
$data = $request->validate([
'name' => ['required', 'max:255'],
'price' => ['required', 'numeric', 'min:1'],
'stock' => ['required', 'integer', 'min:0'],
'category_id' => ['required', 'exists:categories,id'],
]);
$product->update($data);
return redirect()->route('admin.products.index');
}
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
25

## Diapositiva 25

Laravel · Clase 5
Eliminar o desactivar: destroy()
En un e-commerce, borrar puede afectar historial de compras.
ProductController@destroy
delete()
// Borrado físico
$product->delete();
// Alternativa frecuente en catálogo
$product->update([
'active' => false
]);
Elimina el registro. Puede ser problemático si está vinculado a pedidos.
active = false
Oculta el producto sin destruir historial. Suele ser más razonable para catálogos.
Regla profesional: antes de borrar, preguntarse quién depende de ese dato.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
26

## Diapositiva 26

Laravel · Clase 5
{ }
Bloque 4
Relaciones entre Models
DB
belongsTo
hasMany
with
category
MVC
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
27

## Diapositiva 27

Laravel · Clase 5
Relación Product ↔ Category
El e-commerce necesita saber a qué categoría pertenece cada producto.
Category
Product
Indumentaria
Remera
Lectura en lenguaje natural
Lectura inversa
Una categoría puede tener muchos productos.
Cada producto pertenece a una categoría.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
28

## Diapositiva 28

Laravel · Clase 5
belongsTo y hasMany en código
Laravel expresa la relación desde ambos Models.
Product.php
Category.php
class Product extends Model
{
public function category()
{
return $this->belongsTo(Category::class);
}
}
class Category extends Model
{
public function products()
{
return $this->hasMany(Product::class);
}
}
belongsTo se lee desde el producto. hasMany se lee desde la categoría.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
29

## Diapositiva 29

Laravel · Clase 5
Usar relaciones en Controller y Blade
El Controller pide datos; Blade los presenta. La relación permite leer $product->category.
Controller
Blade
public function index()
{
$products = Product::with('category')
->orderBy('name')
->paginate(10);
return view('admin.products.index', compact('products'));
}
@foreach ($products as $product)
<tr>
<td>{{ $product->name }}</td>
<td>{{ $product->category->name }}</td>
<td>${{ $product->price }}</td>
</tr>
@endforeach
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
30

## Diapositiva 30

Laravel · Clase 5
with(): traer relaciones cuando la pantalla las necesita
Si una pantalla muestra categoría, conviene pedirla junto con los productos.
Sin with()
Con with()
Blade podría provocar muchas consultas: una para productos y luego una por cada categoría.
El Controller pide productos con categoría desde el inicio: Product::with("category").
Controller
Product
Category
Blade
with(category)
consulta
relación
muestra
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
31

## Diapositiva 31

Laravel · Clase 5
Relación central del e-commerce: Order y OrderItem
Una compra completa no es una sola fila de producto.
User
Order
OrderItem
Product
compra
pedido
ítems
referencia
orders
order_items
products
Datos de la compra completa: user_id, status, total, fecha, medio de pago.
Detalle de cada producto comprado: product_id, quantity, unit_price, subtotal.
Catálogo actual. Sus datos pueden cambiar después de una compra.
Regla: el precio comprado debe guardarse en order_items para conservar historial.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
32

## Diapositiva 32

Laravel · Clase 5
Métodos y scopes en el Model
Si una consulta se repite, conviene darle nombre y mover intención al Model.
Product.php
Controller
class Product extends Model
{
public function hasStock(): bool
{
return $this->stock > 0;
}
public function scopeAvailable($query)
{
return $query->where('active', true)
->where('stock', '>', 0);
}
}
$products = Product::available()
->orderBy('name')
->get();
Ventaja
Product::available() comunica intención mejor que repetir where(...) en cada Controller.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
33

## Diapositiva 33

Laravel · Clase 5
{ }
Bloque 5
Práctica guiada y cierre
DB
diseño
código
discusión
MVC
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
34

## Diapositiva 34

Laravel · Clase 5
Mini práctica 1: diseñar migrations del catálogo
Trabajo en parejas o individual. 8 minutos + puesta en común.
Consigna
Pistas
Para su e-commerce, definir una tabla categories y una tabla products. Indicar campos, tipos aproximados y cuál es la clave foránea.
¿Qué datos necesita el catálogo? ¿Qué campos deberían ser obligatorios? ¿Qué dato permite publicar/despublicar sin borrar?
Tabla
Campo
Tipo
Justificación
categories
name
string
nombre visible
products
price
decimal
precio
products
category_id
foreignId
relación
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
35

## Diapositiva 35

Laravel · Clase 5
Mini práctica 2: escribir consultas Eloquent
Resolver oralmente o en código. 10 minutos.
Necesidad del sistema
Consulta esperada aproximada
Listar productos activos
Product::where("active", true)->get()
Mostrar últimos productos creados
Product::orderBy("created_at", "desc")->paginate(10)
Buscar un producto por ID
Product::findOrFail($id)
Listar productos con categoría
Product::with("category")->get()
Crear producto validado
$data = $request->validate(...); Product::create($data);
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
36

## Diapositiva 36

Laravel · Clase 5
Errores frecuentes que vamos a evitar
Son errores típicos al pasar de SQL/PHP suelto a Laravel.
Error
Corrección conceptual
Crear tablas a mano y olvidar migrations
La estructura debe quedar en código versionable.
Poner consultas en Blade
Blade muestra; no debería buscar datos.
No definir $fillable
Product::create($data) necesita controlar campos permitidos.
Confundir tabla con Model
products es tabla; Product es clase.
Eliminar productos con pedidos asociados
Pensar historial: quizá conviene desactivar.
No usar relaciones
Si hay category_id, también debería existir category().
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
37

## Diapositiva 37

Laravel · Clase 5
Ticket de salida
Responder individualmente en 5 minutos antes de cerrar.
1
2
1
2
¿Qué diferencia hay entre una migration y un Model?
¿Para qué sirve $fillable?
3
4
3
4
¿Qué significa que Product belongsTo Category?
¿Por qué usar with("category") en un listado?
5
5
¿Qué consulta Eloquent usarías para listar productos activos con stock?
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
38

## Diapositiva 38

Laravel · Clase 5
Tarea para la próxima clase
Conectar el TP1 con una primera estructura real de Laravel.
Modelo de referencia
Entrega / avance
Product::with('category')
->where('active', true)
->where('stock', '>', 0)
->orderBy('name')
->get();
• Revisar los 8 campos de products del TP1.
• Definir migration de products.
• Definir migration de categories.
• Crear Model Product y Category.
• Escribir belongsTo / hasMany.
• Proponer 3 consultas Eloquent útiles para su catálogo.
La próxima clase puede avanzar hacia carga real de productos, formularios completos y manejo de imágenes.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
39

## Diapositiva 39

Laravel · Clase 5
{ }
Hoy bajamos MVC
a datos reales
DB
Migrations definen tablas. Models representan entidades. Eloquent consulta y persiste. Relaciones conectan el sistema.
MVC
MVC
Eloquent
DB
Proyecto integrador
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
40
