# Clase_3_Laravel_MVC_Nueva_Escuela

Total: 39 diapositivas

## Diapositiva 1

Laravel · Clase 3
MVC aplicado
en Laravel
{ }
Route
Rutas, controladores, modelos y vistas sobre el caso del e-commerce funcional
Controller
Model
View
Nueva Escuela de Diseño y Comunicación
1

## Diapositiva 2

Laravel · Clase 3
Punto de partida: qué ya debería estar claro
Retomamos desde SQL CRUD, POO y el primer puente hacia Laravel.
PHP
Lenguaje del servidor: procesa formularios, aplica lógica y genera respuestas.
MySQL
Persistencia: guarda productos, usuarios, pedidos e ítems de pedido.
POO
Organización: clases con datos y comportamiento.
Laravel
Framework: ordena rutas, controladores, modelos, vistas y seguridad.
Vamos a convertir ese puente en un mapa operativo para desarrollar el e-commerce.
Referencia de continuidad: al final de la clase anterior aparecía MVC como Route → Controller → Model → View. Ahora lo vamos a desarmar con ejemplos.
2
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 3

Laravel · Clase 3
Bloque 1
MVC como mapa de responsabilidades
Route
Controller
Model
View
MVC
5

## Diapositiva 4

Laravel · Clase 3
Por qué MVC es tan importante
El problema no es “hacer funcionar” una pantalla. El problema es que el sistema pueda crecer.
Sin separación
HTML + SQL + validación + reglas de negocio en el mismo archivo.
Con MVC
Cada parte tiene una responsabilidad clara y se puede mantener sin romper todo.
En un e-commerce esto escala rápido: catálogo, carrito, stock, usuarios, pedidos, pagos, envíos y panel administrador.
¿dónde vive esta decisión?
6
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 5

Laravel · Clase 3
Definición operativa de MVC
Una arquitectura para separar datos, coordinación y presentación.
MODEL
Datos + reglas del dominio
Ej.: Product, Order, User
CONTROLLER
Coordina la petición
Ej.: ProductController
VIEW
Presenta información
Ej.: Blade HTML
La ruta no es una de las tres letras de MVC, pero en Laravel es la puerta de entrada: conecta una URL con un Controller.
7
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 6

Laravel · Clase 3
Modelo mental: una petición es un recorrido
El usuario no “entra a una pantalla”: dispara una petición que atraviesa capas.
Usuario
Navegador
Route
URL + verbo
Controller
coordina
Model
datos
View
Blade
HTML
respuesta
Ejemplo: GET /productos
1. La ruta reconoce la URL.
2. El controlador pide productos.
3. El modelo consulta datos.
4. La vista arma el HTML.
5. El usuario ve el catálogo.
8
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 7

Laravel · Clase 3
Mapa de carpetas en Laravel
MVC también es una decisión de organización del proyecto.
routes/
web.php
app/Http/Controllers/
ProductController.php
app/Models/
Product.php
resources/views/
products/
index.blade.php
show.blade.php
Traducción rápida
routes/web.php: define entradas.
Controller: recibe y coordina.
Model: representa datos y relaciones.
View: muestra información sin decidir reglas centrales.
9
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 8

Laravel · Clase 3
Bloque 2
Ejemplo 1: catálogo de productos
GET
Route
Controller
Model
View
10

## Diapositiva 9

Laravel · Clase 3
Caso 1: listar productos disponibles
Pantalla pública del e-commerce: el usuario ve el catálogo.
URL
/productos
Route
web.php
ProductController
index()
Product
Model
View
index
Objetivo funcional: mostrar solo productos activos/disponibles, ordenados y con los datos necesarios para la pantalla.
11
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 10

Laravel · Clase 3
Paso 1: la ruta define la entrada
Una Route responde a una URL y a un método HTTP.
// routes/web.php
use App\Http\Controllers\ProductController;
Route::get('/productos', [ProductController::class, 'index'])
->name('products.index');
Qué está diciendo esta ruta
GET /productos: pedido de lectura.
ProductController::class: clase que atenderá la petición.
index: método que se ejecutará.
name: nombre interno para generar enlaces.
La ruta no debería tener SQL, HTML ni reglas largas. Su trabajo principal es derivar.
12
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 11

Laravel · Clase 3
Paso 2: el Controller coordina
El Controller decide qué pedir y qué devolver, sin hacer todo él mismo.
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;
use App\Models\Product;
class ProductController extends Controller
{
public function index()
{
$products = Product::where('stock', '>', 0)
->orderBy('name')
->get();
return view('products.index', compact('products'));
}
}
Responsabilidad del Controller
Recibe la petición.
Pide datos al Model.
Elige la View.
Entrega datos a la View.
No debería mezclar HTML ni consultas SQL manuales extensas.
13
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 12

Laravel · Clase 3
Paso 3: el Model representa los datos
El Model conecta la clase del dominio con una tabla y sus relaciones.
// app/Models/Product.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
protected $fillable = [
'name',
'price',
'stock',
'category_id'
];
public function hasStock(): bool
{
return $this->stock > 0;
}
}
Qué representa Product
La tabla products.
Una fila de producto.
Sus atributos principales.
Relaciones futuras: categoría, imágenes, order_items.
Reglas simples del producto.
14
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 13

Laravel · Clase 3
Paso 4: la View muestra, no decide todo
Blade recibe datos y arma HTML para el usuario.
<!-- resources/views/products/index.blade.php -->
<h1>Productos</h1>
@foreach ($products as $product)
<article>
<h2>{{ $product->name }}</h2>
<p>${{ $product->price }}</p>
@if ($product->hasStock())
<a href="{{ route('products.show', $product) }}">
Ver detalle
</a>
@endif
</article>
@endforeach
Responsabilidad de la View
Recorrer datos recibidos.
Imprimir HTML.
Usar condiciones simples de presentación.
No conectarse directamente a MySQL.
No definir reglas de compra críticas.
15
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 14

Laravel · Clase 3
El mismo flujo, explicado como conversación
Una forma simple de hacerlo memorable.
Route
“Alguien pidió /productos. Le aviso al ProductController.”
Controller
“Necesito productos disponibles. Product, traeme los datos.”
Model
“Consulto la tabla products y devuelvo objetos Product.”
View
“Con esos productos armo el HTML del catálogo.”
GET /productos
→ ProductController@index
→ Product::where('stock', '>', 0)->get()
→ view('products.index', ['products' => $products])
→ HTML para el navegador
16
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 15

Laravel · Clase 3
Qué NO debería hacer cada pieza
MVC también se aprende por descarte.
Route
No contiene lógica larga ni SQL. Solo conecta entrada con acción.
Controller
No imprime HTML. No debería concentrar todas las reglas de negocio.
Model
No decide qué pantalla mostrar. No conoce la experiencia visual.
View
No consulta directamente la base. No debería validar reglas críticas.
Regla profesional: si un archivo “sabe demasiado”, probablemente está rompiendo responsabilidades.
17
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 16

Laravel · Clase 3
Antes y después: del PHP suelto a MVC
Lo que antes estaba en guardar.php ahora se distribuye.
// productos.php
$con = new PDO(...);
$sql = 'SELECT * FROM products';
$rows = $con->query($sql);
echo '<h1>Productos</h1>';
foreach ($rows as $row) {
echo '<p>' . $row['name'] . '</p>';
}
// Laravel MVC
Route::get('/productos', [ProductController::class, 'index']);
// Controller
$products = Product::all();
return view('products.index', compact('products'));
// View
@foreach ($products as $product)
{{ $product->name }}
@endforeach
Problema
El mismo archivo conecta DB, consulta datos y genera HTML. Funciona, pero escala mal.
Ventaja
Cada archivo tiene un rol: entrada, coordinación, datos y presentación.
18
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 17

Laravel · Clase 3
Bloque 3
Ejemplo 2: detalle de producto
GET
Parámetros
show
Model
19

## Diapositiva 18

Laravel · Clase 3
Caso 2: ver el detalle de un producto
La URL trae un dato: qué producto quiere ver el usuario.
// routes/web.php
Route::get('/productos/{product}', [ProductController::class, 'show'])
->name('products.show');
{product}
Es un parámetro de ruta.
Laravel puede usarlo para encontrar el producto correspondiente.
Ejemplo de URL real:
/productos/15
/productos/15
show(Product $product)
products.show
20
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 19

Laravel · Clase 3
Controller para detalle: show()
Ahora el Controller recibe un producto concreto.
use App\Models\Product;
class ProductController extends Controller
{
public function show(Product $product)
{
return view('products.show', [
'product' => $product
]);
}
}
Qué ocurre acá
Laravel interpreta el parámetro de URL.
Busca el registro en products.
Entrega un objeto Product al método show.
El Controller pasa ese objeto a la View.
La función show() no necesita armar SQL manual ni imprimir HTML: coordina.
21
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 20

Laravel · Clase 3
View de detalle: presentar un objeto
Blade usa las propiedades del objeto Product.
<!-- resources/views/products/show.blade.php -->
<h1>{{ $product->name }}</h1>
<p>Precio: ${{ $product->price }}</p>
<p>Stock disponible: {{ $product->stock }}</p>
@if ($product->hasStock())
<button>Agregar al carrito</button>
@else
<p>Producto sin stock</p>
@endif
Lectura didáctica
$product no es un array suelto.
Es un objeto del modelo Product.
Tiene propiedades.
Puede tener métodos.
La vista lo utiliza para mostrar información.
22
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 21

Laravel · Clase 3
Mini práctica 1: ¿qué es Model, View o Controller?
Responder rápido, sin escribir código.
A. Mostrar el precio con formato en una tarjeta de producto.
B. Buscar productos activos ordenados por nombre.
C. Recibir POST /admin/productos y decidir si guardar o devolver errores.
D. Definir que un producto pertenece a una categoría.
E. Renderizar una tabla HTML con productos del admin.
F. Redirigir al listado después de crear correctamente.
Puesta en común: justificar no solo la respuesta, sino la responsabilidad.
23
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 22

Laravel · Clase 3
Bloque 4
Ejemplo 3: crear productos en el admin
GET
POST
Form
Store
Validation
24

## Diapositiva 23

Laravel · Clase 3
Crear producto requiere dos rutas
Una ruta muestra el formulario. Otra procesa el envío.
// routes/web.php
Route::get('/admin/productos/create', [ProductController::class, 'create'])
->name('admin.products.create');
Route::post('/admin/productos', [ProductController::class, 'store'])
->name('admin.products.store');
Diferencia clave
GET create: devuelve una vista con el formulario vacío.
POST store: recibe datos, valida, guarda y redirige.
No son lo mismo aunque ambos estén vinculados a “crear”.
GET
formulario
POST
guardar
Método HTTP = intención de la petición.
25
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 24

Laravel · Clase 3
Controller: create() devuelve el formulario
Cuando la petición es GET, normalmente no guarda datos.
Responsabilidad
No valida.
No inserta.
No modifica stock.
Solo elige qué vista se muestra para que el admin cargue datos.
public function create()
{
return view('admin.products.create');
}
GET debe ser seguro e idempotente en términos prácticos: pedir la pantalla no debería cambiar la base de datos.
26
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 25

Laravel · Clase 3
View: formulario de creación
La View genera el HTML que el admin completa.
<!-- resources/views/admin/products/create.blade.php -->
<form method="POST" action="{{ route('admin.products.store') }}">
@csrf
<input name="name" placeholder="Nombre">
<input name="price" placeholder="Precio">
<input name="stock" placeholder="Stock">
<button>Guardar producto</button>
</form>
Puntos importantes
El action apunta a la ruta store.
method="POST" indica envío de datos.
@csrf protege el formulario.
La View no decide si el precio es válido: solo presenta campos y errores.
27
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 26

Laravel · Clase 3
Controller: store() procesa el formulario
POST implica recibir datos, validar, guardar y responder.
public function store(Request $request)
{
$data = $request->validate([
'name' => ['required', 'max:255'],
'price' => ['required', 'numeric', 'min:1'],
'stock' => ['required', 'integer', 'min:0'],
]);
Product::create($data);
return redirect()
->route('admin.products.index')
->with('success', 'Producto creado');
}
store() concentra el flujo
1. Recibe Request.
2. Valida datos.
3. Crea un Product.
4. Redirige.
5. Envía mensaje de estado.
Una buena store() se puede leer como una secuencia de decisiones, no como un bloque caótico.
28
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 27

Laravel · Clase 3
Validación: frontera entre usuario y sistema
Nunca guardamos datos sin controlar formato y reglas mínimas.
Entrada del usuario
name = ""
price = "abc"
stock = -5
validate()
required
numeric
integer
min
max
Datos aceptables
name válido
price > 0
stock >= 0
La validación se ubica antes de crear el Model. Es una puerta de control.
29
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 28

Laravel · Clase 3
CRUD de productos visto desde MVC
El panel admin se puede planificar antes de programar.
Acción
Ruta
Controller
View
Listar
GET /admin/productos
index()
admin.products.index
Crear form
GET /admin/productos/create
create()
admin.products.create
Guardar
POST /admin/productos
store()
redirect
Editar form
GET /admin/productos/{id}/edit
edit()
admin.products.edit
Actualizar
PUT /admin/productos/{id}
update()
redirect
Borrar
DELETE /admin/productos/{id}
destroy()
redirect
Esta tabla es el mapa inicial de un Resource Controller.
30
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 29

Laravel · Clase 3
Resource Controller: convención que ordena CRUD
Laravel ya tiene nombres esperados para acciones frecuentes.
Genera rutas convencionales para index, create, store, show, edit, update y destroy.
Route::resource('admin/productos', ProductController::class);
Por qué conviene
Usa nombres estándar.
Reduce rutas repetidas.
Hace predecible el proyecto.
Facilita que otros desarrolladores entiendan el código.
Advertencia didáctica
Primero necesitamos entender qué hace cada ruta. Después usamos la convención para escribir menos.
31
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 30

Laravel · Clase 3
Bloque 5
Diseñar con MVC: decisiones profesionales
Responsabilidades
Errores
Refactor
32

## Diapositiva 31

Laravel · Clase 3
Model no es “solo una tabla”
En Laravel, el Model es una clase que representa una entidad del dominio.
Tabla products
id
name
price
stock
category_id
Model Product
fillable
relaciones
scopes
métodos
reglas simples
Objeto $product
$product->name
$product->price
$product->hasStock()
La tabla persiste datos. El Model permite trabajar esos datos como objetos dentro de PHP.
33
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 32

Laravel · Clase 3
Controller no debería ser un “Dios”
Un Controller demasiado grande suele indicar responsabilidades mal ubicadas.
Mal síntoma
El método store() tiene 150 líneas.
Calcula envíos.
Procesa pagos.
Descuenta stock.
Arma HTML.
Envía mails.
Mejor dirección
Controller coordina.
Model conoce relaciones.
Servicios resuelven operaciones complejas.
View presenta.
Requests validan.
Para este tramo del curso alcanza con una regla: controller fino, responsabilidades claras.
34
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 33

Laravel · Clase 3
Refactor mental: dividir un archivo mezclado
Ejercicio guiado para detectar responsabilidades.
// archivo mezclado
if ($_POST) {
validar campos
INSERT INTO products...
echo "Producto creado";
}
SELECT * FROM products
foreach (...) {
echo "<tr>...</tr>";
}
Separación MVC
Route: define GET/POST.
Controller: create(), store(), index().
Model: Product y tabla products.
View: formulario, listado y mensajes.
Consigna oral: marcar con colores qué línea iría a cada capa.
35
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 34

Laravel · Clase 3
Mini práctica 2: diseñar el MVC del carrito
Aplicar el patrón a una función nueva del e-commerce.
Funcionalidad
“Agregar un producto al carrito”
Definir
1 ruta
1 método del controller
1 modelo involucrado
1 vista o redirección
1 validación mínima
Pistas
¿Es GET o POST?
¿Qué dato viaja?
¿Quién controla el stock?
¿Qué ve el usuario después?
Tiempo sugerido: 8 minutos + 7 minutos de puesta en común.
36
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 35

Laravel · Clase 3
Respuesta modelo: agregar al carrito
Una posible solución para discutir después de la práctica.
Route::post('/carrito/items', [CartController::class, 'store'])
->name('cart.items.store');
class CartController extends Controller
{
public function store(Request $request)
{
$data = $request->validate([
'product_id' => ['required', 'exists:products,id'],
'quantity' => ['required', 'integer', 'min:1'],
]);
$product = Product::findOrFail($data['product_id']);
// acá verificaremos stock y agregaremos al carrito
return redirect()->route('cart.index');
}
}
Lectura MVC
Route: POST porque cambia estado.
Controller: valida y coordina.
Model: Product aporta datos y stock.
View: formulario con product_id y quantity.
Respuesta: redirección al carrito.
37
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 36

Laravel · Clase 3
Errores frecuentes al aprender MVC
Estos problemas van a aparecer en los primeros proyectos.
1. Poner SQL o Eloquent directamente en la View.
2. Creer que el Controller es el único lugar donde va toda la lógica.
3. Crear Models vacíos sin relaciones ni comportamiento.
4. Confundir create() con store(), o edit() con update().
5. Usar GET para acciones que modifican datos.
6. No validar antes de crear o actualizar registros.
La corrección no es “porque Laravel lo pide”, sino porque cada responsabilidad necesita un lugar.
38
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 37

Laravel · Clase 3
Ticket de salida
Responder individualmente en 5 minutos antes de cerrar.
1. Explicá MVC sin usar la palabra “organizar”.
2. ¿Qué diferencia hay entre create() y store()?
3. ¿Qué parte de Laravel se ocupa de mostrar HTML?
4. ¿Por qué Product puede considerarse Model y objeto a la vez?
5. Si una vista tiene SQL, ¿qué problema aparece?
Objetivo de evaluación rápida: verificar comprensión de responsabilidades, no memoria de sintaxis.
39
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 38

Laravel · Clase 3
Tarea para la próxima clase
Traer una primera estructura MVC pensada para el proyecto integrador.
Elegir una entidad central del e-commerce
Producto, categoría, usuario, pedido o carrito.
Definir 4 rutas posibles
Listar, ver detalle, crear y guardar.
Proponer Controller + métodos
Ej.: ProductController@index, show, create, store.
Identificar Views necesarias
index.blade.php, show.blade.php, create.blade.php.
Indicar qué hace el Model
Tabla, atributos, relaciones y una regla simple.
Entrega breve: una hoja o captura con el mapa MVC. No hace falta que funcione todavía.
40
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 39

Laravel · Clase 3
Cierre
Hoy convertimos PHP + MySQL + POO en una arquitectura de trabajo.
PHP
lógica
MySQL
datos
POO
objetos
MVC
responsabilidades
Laravel
e-commerce
Idea rectora: Laravel no es magia. Es una forma disciplinada de ubicar rutas, controladores, modelos, vistas, validaciones y respuestas.
41
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
