# Clase_4_Laravel_MVC_CRUD_Blade_Eloquent_Nueva_Escuela

Total: 42 diapositivas

## Diapositiva 1

Laravel · Clase 4
MVC en práctica:
CRUD, Blade y Eloquent
{ }
DB
Partimos desde el Bloque 3: del mapa MVC al desarrollo del panel de productos.
MVC
Route
Controller
Model
View
CRUD
Nueva Escuela de Diseño y Comunicación
1

## Diapositiva 2

Laravel · Clase 4
Dónde retomamos
La clase anterior llegó hasta el puente MVC y ahora lo vamos a convertir en práctica operativa.
Ya vimos
Route → Controller → Model → View
Hoy reforzamos
con más ejemplos y CRUD real
Próximo paso
formularios + validación + Eloquent
No alcanza con saber qué significa MVC. Tenemos que poder decidir, frente a cada funcionalidad del e-commerce, qué archivo recibe cada responsabilidad.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
2

## Diapositiva 3

Laravel · Clase 4
Objetivos de la clase
Al terminar, deberían poder diseñar y leer un CRUD de productos con criterio MVC.
1
2
Explicar MVC con ejemplos
Leer rutas de Laravel
Diferenciar responsabilidades sin depender de definiciones memorizadas.
Distinguir GET, POST, PUT y DELETE según la intención de la acción.
3
4
Construir flujo CRUD
Conectar Blade y Eloquent
Ubicar index, create, store, edit, update y destroy.
Entender qué muestra la vista y qué resuelve el modelo.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
3

## Diapositiva 4

Laravel · Clase 4
{ }
Bloque 1
MVC con ejemplos
MVC
Route    Controller    Model    View
5

## Diapositiva 5

Laravel · Clase 4
MVC no es una carpeta: es una responsabilidad
La pregunta importante no es “dónde está el archivo”, sino “qué decisión toma”.
Route
¿Qué URL y método entran?
Controller
¿Qué flujo coordina?
Model
¿Qué datos y reglas conoce?
View
¿Qué ve el usuario?
Ejemplo: “mostrar catálogo” no es una sola tarea. Es una cadena: recibir URL, buscar productos, elegir pantalla y renderizar HTML.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
6

## Diapositiva 6

Laravel · Clase 4
Ejemplo 1: ver catálogo de productos
Caso: el usuario entra a /productos.
routes/web.php
ProductController@index
Route::get('/productos', [ProductController::class, 'index'])
->name('products.index');
public function index()
{
$products = Product::where('stock', '>', 0)
->orderBy('name')
->get();
return view('products.index', compact('products'));
}
La ruta recibe. El controller coordina. El model consulta. La view presenta.
Lectura MVC
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
7

## Diapositiva 7

Laravel · Clase 4
Ejemplo 2: ver detalle de un producto
Caso de lectura con parámetro: el ID o slug viaja en la URL.
Route con parámetro
Controller show()
Route::get('/productos/{product}', [ProductController::class, 'show'])
->name('products.show');
public function show(Product $product)
{
return view('products.show', [
'product' => $product
]);
}
URL
/productos/15
Route
{product}
Model
Product #15
View
show.blade.php
HTML
detalle
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
8

## Diapositiva 8

Laravel · Clase 4
Ejemplo 3: agregar producto al carrito
Caso de escritura: el usuario modifica el estado de la aplicación.
Cuando una acción cambia datos o estado, no debería ser GET.
CartController@store
Route POST
public function store(Request $request)
{
$data = $request->validate([
'product_id' => ['required', 'exists:products,id'],
'quantity' => ['required', 'integer', 'min:1'],
]);
$product = Product::findOrFail($data['product_id']);
// validar stock y agregar al carrito
return redirect()->route('cart.index');
}
Route::post('/carrito/items', [CartController::class, 'store'])
->name('cart.items.store');
MVC aplicado: Route define la entrada, Controller valida y coordina, Product aporta datos, la respuesta redirige al carrito.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
9

## Diapositiva 9

Laravel · Clase 4
La confusión clásica: create() no es store()
Ambas acciones están relacionadas con crear, pero tienen responsabilidades distintas.
GET /admin/products/create
create()
Muestra el formulario
POST /admin/products
store()
Valida y guarda datos
create() prepara la pantalla.
store() procesa lo enviado por esa pantalla.
Regla práctica
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
10

## Diapositiva 10

Laravel · Clase 4
La otra confusión: edit() no es update()
Editar y actualizar tampoco son lo mismo.
GET /admin/products/{product}/edit
edit()
Muestra formulario con datos existentes
PUT /admin/products/{product}
update()
Valida y modifica el registro
edit() lee y muestra.
update() recibe, valida y persiste cambios.
Regla práctica
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
11

## Diapositiva 11

Laravel · Clase 4
{ }
Bloque 2
CRUD de productos en Laravel
MVC
index    create    store    edit    update    destroy
13

## Diapositiva 12

Laravel · Clase 4
Resource Controller: el mapa estándar del CRUD
Laravel propone una convención para operaciones frecuentes.
Acción
Ruta
Método
Responsabilidad
Listar
GET /admin/products
index()
Mostrar listado
Formulario alta
GET /admin/products/create
create()
Mostrar formulario
Guardar
POST /admin/products
store()
Validar + crear
Detalle
GET /admin/products/{product}
show()
Mostrar uno
Formulario edición
GET /admin/products/{product}/edit
edit()
Cargar datos existentes
Actualizar
PUT/PATCH /admin/products/{product}
update()
Validar + modificar
Eliminar
DELETE /admin/products/{product}
destroy()
Borrar/desactivar
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
14

## Diapositiva 13

Laravel · Clase 4
Route::resource: escribir menos, ordenar más
Después de entender cada ruta, podemos usar la convención.
• Genera rutas para index, create, store, show, edit, update y destroy.
• Usa nombres previsibles: admin.products.index, admin.products.store, etc.
• Facilita que otros desarrolladores entiendan el proyecto.
• No reemplaza pensar responsabilidades: solo reduce repetición.
routes/web.php
use App\Http\Controllers\ProductController;
Route::resource('admin/products', ProductController::class);
Primero diseño el flujo.
Después lo implemento con la convención de Laravel.
Convención profesional
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
15

## Diapositiva 14

Laravel · Clase 4
index(): listar productos del administrador
El admin necesita una tabla de gestión, no solo el catálogo público.
ProductController@index
• Lee productos desde Product.
• Ordena por fecha de creación.
• Pagina resultados para no cargar todo de golpe.
• Devuelve una vista del panel administrador.
public function index()
{
$products = Product::orderBy('created_at', 'desc')
->paginate(10);
return view('admin.products.index', compact('products'));
}
index() no crea, no actualiza y no borra: solamente prepara el listado.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
16

## Diapositiva 15

Laravel · Clase 4
create(): mostrar el formulario de alta
GET prepara una pantalla; no cambia la base de datos.
ProductController@create
• Puede pedir datos auxiliares, como categorías.
• Devuelve la vista del formulario.
• No guarda nada todavía.
• Prepara al usuario para enviar un POST.
public function create()
{
$categories = Category::orderBy('name')->get();
return view('admin.products.create', compact('categories'));
}
Pregunta de diseño: ¿qué necesita saber el formulario para que el admin cargue un producto completo?
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
17

## Diapositiva 16

Laravel · Clase 4
store(): validar y crear el producto
POST recibe datos del formulario y decide si puede persistirlos.
ProductController@store
• Request trae datos del formulario.
• validate() controla entrada del usuario.
• Product::create() crea una fila en products.
• redirect() evita reenviar el formulario al refrescar.
• with() envía un mensaje temporal.
public function store(Request $request)
{
$data = $request->validate([
'name' => ['required', 'max:255'],
'price' => ['required', 'numeric', 'min:1'],
'stock' => ['required', 'integer', 'min:0'],
'category_id' => ['required', 'exists:categories,id'],
]);
Product::create($data);
return redirect()
->route('admin.products.index')
->with('success', 'Producto creado');
}
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
18

## Diapositiva 17

Laravel · Clase 4
edit() y update(): modificar un producto
Editar primero muestra datos existentes; actualizar persiste cambios.
edit()
update()
public function edit(Product $product)
{
$categories = Category::orderBy('name')->get();
return view('admin.products.edit', compact('product', 'categories'));
}
public function update(Request $request, Product $product)
{
$data = $request->validate([
'name' => ['required', 'max:255'],
'price' => ['required', 'numeric', 'min:1'],
'stock' => ['required', 'integer', 'min:0'],
]);
$product->update($data);
return redirect()->route('admin.products.index');
}
Diferencia esencial: edit() lee para mostrar; update() recibe y modifica.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
19

## Diapositiva 18

Laravel · Clase 4
destroy(): borrar no siempre es lo mejor
En e-commerce, el historial de pedidos puede depender de productos existentes.
Opción simple
Opción más segura
public function destroy(Product $product)
{
$product->delete();
return redirect()->route('admin.products.index')
->with('success', 'Producto eliminado');
}
public function destroy(Product $product)
{
$product->update(['active' => false]);
return redirect()->route('admin.products.index')
->with('success', 'Producto desactivado');
}
Si un producto ya forma parte de pedidos, muchas veces conviene desactivarlo en lugar de eliminarlo físicamente.
Regla profesional
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
20

## Diapositiva 19

Laravel · Clase 4
CRUD de productos visto como recorrido MVC
Cada acción sigue la misma lógica, aunque cambie el método HTTP.
Listar
GET
index()
Product
admin.products.index
Crear
GET
create()
Product
admin.products.create
Guardar
POST
store()
Product
redirect
Actualizar
PUT
update()
Product
redirect
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
21

## Diapositiva 20

Laravel · Clase 4
{ }
Bloque 3
Blade, formularios y validación
MVC
Blade    @csrf    errors    route()
22

## Diapositiva 21

Laravel · Clase 4
Blade: la View de Laravel
Blade permite mezclar HTML con directivas de presentación, sin convertir la vista en un archivo de lógica total.
products/index.blade.php
• Recibe variables que le entrega el Controller.
• Usa {{ }} para imprimir datos escapados.
• Usa @foreach, @if, @csrf, @error como directivas.
• No debería consultar directamente la base de datos.
<h1>Productos</h1>
@foreach ($products as $product)
<article>
<h2>{{ $product->name }}</h2>
<p>${{ $product->price }}</p>
</article>
@endforeach
La vista presenta información; no debería convertirse en el cerebro del sistema.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
23

## Diapositiva 22

Laravel · Clase 4
Formulario de creación: la View envía datos
El formulario apunta a la ruta store y usa POST.
admin/products/create.blade.php
• method="POST" expresa intención de crear.
• action apunta a una ruta nombrada.
• @csrf agrega protección contra envíos falsificados.
• Los name deben coincidir con lo que valida el Controller.
<form method="POST" action="{{ route('admin.products.store') }}">
@csrf
<input name="name" placeholder="Nombre">
<input name="price" placeholder="Precio">
<input name="stock" placeholder="Stock">
<button>Guardar producto</button>
</form>
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
24

## Diapositiva 23

Laravel · Clase 4
@csrf: seguridad mínima del formulario
Laravel exige token CSRF en formularios que modifican estado.
Usuario legítimo
completa formulario
Formulario Blade
incluye @csrf
Laravel verifica
token
Controller
procesa store()
CSRF protege formularios para que una acción sensible no pueda dispararse desde otro sitio sin autorización.
Definición simple
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
25

## Diapositiva 24

Laravel · Clase 4
Mostrar errores de validación en Blade
Validar sin mostrar mensajes deja al usuario perdido.
Errores por campo
Mantener datos viejos
<input name="price" value="{{ old('price') }}">
@error('price')
<p class="error">{{ $message }}</p>
@enderror
<input name="name" value="{{ old('name') }}">
<textarea name="description">
{{ old('description') }}
</textarea>
• old() recupera lo que el usuario ya escribió.
• @error muestra el mensaje asociado al campo.
• El Controller valida; la View ayuda a corregir.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
26

## Diapositiva 25

Laravel · Clase 4
route(): enlazar por nombre, no por URL escrita a mano
Las rutas nombradas hacen el proyecto más mantenible.
Route
Blade
Route::get('/productos/{product}', [ProductController::class, 'show'])
->name('products.show');
<a href="{{ route('products.show', $product) }}">
Ver detalle
</a>
Si mañana cambia la URL, no tengo que buscar links escritos a mano por todo el proyecto.
Ventaja
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
27

## Diapositiva 26

Laravel · Clase 4
Métodos HTTP: intención de la petición
El método no es un detalle técnico; indica qué quiere hacer el usuario.
Método
Intención
Ejemplo
GET
Leer / mostrar
Ver listado o formulario
POST
Crear / enviar
Guardar producto nuevo
PUT/PATCH
Actualizar
Modificar precio o stock
DELETE
Eliminar / desactivar
Quitar producto del admin
Regla: si cambia datos o estado, no debería ser GET.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
28

## Diapositiva 27

Laravel · Clase 4
{ }
Bloque 4
Eloquent: modelos y relaciones
MVC
Product    Category    fillable    belongsTo
29

## Diapositiva 28

Laravel · Clase 4
Eloquent: trabajar tablas como objetos
Eloquent es el ORM de Laravel: conecta Models con tablas de la base de datos.
Tabla
products
Model
Product.php
Objeto
$product
Vista
Blade
Un Model en Laravel es una clase PHP que representa una entidad del sistema y permite consultar, crear, actualizar o relacionar registros de una tabla.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
30

## Diapositiva 29

Laravel · Clase 4
Product.php: el Model base
Este archivo será el puente entre PHP, MySQL y Laravel.
app/Models/Product.php
• Product representa la tabla products.
• $fillable indica qué campos pueden cargarse masivamente.
• El Model no define HTML.
• Más adelante sumará relaciones y métodos útiles.
namespace App\Models;use Illuminate\Database\Eloquent\Model;class Product extends Model{    protected $fillable = [        'name',        'description',        'price',        'stock',        'category_id',        'active',    ];}
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
31

## Diapositiva 30

Laravel · Clase 4
$fillable: qué datos acepto crear masivamente
Mass assignment permite Product::create($data), pero debe estar controlado.
Controller
Model
$data = $request->validate([
'name' => ['required'],
'price' => ['required', 'numeric'],
'stock' => ['required', 'integer'],
]);
Product::create($data);
class Product extends Model
{
protected $fillable = [
'name', 'price', 'stock'
];
}
No todo lo que llega desde un formulario debería poder modificar cualquier columna de la base.
Idea central
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
32

## Diapositiva 31

Laravel · Clase 4
Relaciones: Product pertenece a Category
Lo que antes era JOIN en SQL, Laravel puede expresarlo como relación entre Models.
Product.php
Category.php
public function category()
{
return $this->belongsTo(Category::class);
}
public function products()
{
return $this->hasMany(Product::class);
}
categories
id, name
products
category_id
Una categoría tiene muchos productos. Cada producto pertenece a una categoría.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
33

## Diapositiva 32

Laravel · Clase 4
with(): traer relaciones cuando la pantalla las necesita
Si el listado muestra categoría, conviene pedir productos con su categoría.
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
El Controller pide los datos necesarios; Blade los muestra.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
34

## Diapositiva 33

Laravel · Clase 4
Métodos y scopes: consultas reutilizables
Si una condición se repite, conviene darle nombre.
Product.php
Controller
public function hasStock(): bool
{
return $this->stock > 0;
}
public function scopeAvailable($query)
{
return $query->where('active', true)
->where('stock', '>', 0);
}
$products = Product::available()
->orderBy('name')
->get();
Ventaja pedagógica
Product::available() comunica intención mejor que repetir where(...) en todos lados.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
35

## Diapositiva 34

Laravel · Clase 4
Dónde ubicar reglas: una guía práctica
No todo va en el Controller, no todo va en el Model y no todo va en Blade.
Situación
Mejor ubicación inicial
Mostrar mensaje “sin stock”
View
Verificar stock > 0 en un producto
Model
Validar campos enviados por formulario
Request / Controller
Elegir si redirige o muestra vista
Controller
Listar productos activos
Model scope + Controller
Formato visual de tarjeta de producto
View
Regla de oro: cada capa debe saber lo mínimo necesario para cumplir su función.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
36

## Diapositiva 35

Laravel · Clase 4
{ }
Bloque 5
Práctica
MVC
diseño    discusión    ticket
37

## Diapositiva 36

Laravel · Clase 4
Mini práctica 1: diseñar MVC para crear producto
Completar el mapa de responsabilidades:
Pregunta
Respuesta esperada
¿Qué ruta muestra el formulario?
¿Qué ruta guarda el producto?
¿Qué método del Controller muestra?
¿Qué método del Controller guarda?
¿Qué validaciones mínimas hacen?
¿Qué vista se necesita?
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
38

## Diapositiva 37

Laravel · Clase 4
Respuesta modelo: crear producto
Una solución posible para comparar con la práctica.
Pregunta
Respuesta modelo
Formulario
GET /admin/products/create → create()
Guardar
POST /admin/products → store()
View
admin.products.create
Model
Product + Category si elige categoría
Validación
name required, price numeric/min, stock integer/min, category exists
Respuesta
redirect a index con mensaje success
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
39

## Diapositiva 38

Laravel · Clase 4
Mini práctica 2: diseñar MVC para editar producto
Aplicar el mismo razonamiento a un producto existente.
Producto existente
/admin/products/15/edit
Formulario precargado
edit()
Enviar cambios
update()
Preguntas para responder
• ¿Por qué edit() es GET?
• ¿Por qué update() debería ser PUT/PATCH?
• ¿Qué datos necesita la vista edit?
• ¿Qué validaciones no pueden faltar?
• ¿Qué pasa si cambia el precio a 0?
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
40

## Diapositiva 39

Laravel · Clase 4
Errores frecuentes que vamos a evitar
Son problemas típicos cuando MVC todavía está poco claro.
Error
Corrección conceptual
Poner Product::all() dentro de Blade
La View recibe datos; no los busca directamente.
Usar GET para eliminar
Las acciones destructivas deben usar DELETE/POST controlado.
Confundir create con store
create muestra; store procesa.
No validar antes de Product::create
La frontera de entrada debe controlarse.
Controller de 200 líneas
Separar responsabilidades y reutilizar Models/servicios.
La sintaxis se corrige rápido. Las malas responsabilidades se vuelven deuda técnica.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
41

## Diapositiva 40

Laravel · Clase 4
Ticket de salida
Responder individualmente en 5 minutos antes de cerrar.
1. Explicá la diferencia entre create() y store().
2. ¿Qué responsabilidad tiene una Route?
3. ¿Por qué Blade no debería consultar la base directamente?
4. ¿Qué hace $fillable en un Model?
5. ¿Dónde ubicarías la relación Product → Category?
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
42

## Diapositiva 41

Laravel · Clase 4
Tarea para la próxima clase
Conectar el TP1 con una primera estructura MVC del proyecto.
Formato esperado
• Entregar o avanzar el TP1: temática, products, 3 tablas relacionadas y clase Product.
• Elegir una entidad central: Product, Category, Order o Cart.
• Definir 4 rutas posibles: listar, ver detalle, crear y guardar.
• Proponer métodos del Controller y Views necesarias.
• Indicar qué responsabilidad tendría el Model.
Entidad: Product
Rutas:
GET /products -> index() -> products.index
GET /products/{product} -> show() -> products.show
GET /admin/products/create -> create() -> admin.products.create
POST /admin/products -> store() -> redirect
Model:
Product: atributos, relación category(), hasStock()
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
43

## Diapositiva 42

Laravel · Clase 4
Hoy convertimos MVC
en una herramienta de diseño
MVC
Route define entrada. Controller coordina. Model trabaja con datos y reglas. View presenta.
Route
Controller
Model
View
CRUD
Eloquent
Próximo paso: crear el proyecto Laravel y materializar estas rutas, controladores, modelos y vistas.
Nueva Escuela de Diseño y Comunicación
44
