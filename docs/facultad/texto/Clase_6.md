# Clase_6_Laravel_Formularios_Validacion_Imagenes_Nueva_Escuela

Total: 24 diapositivas

## Diapositiva 1

Laravel · Clase 6
Formularios, validación
y carga de imágenes
{ }
Del Model a un producto realmente administrable
DB
Request
Validation
Storage
Blade
MVC
1
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 2

Laravel · Clase 6
Dónde retomamos
La clase anterior bajó MVC a datos reales: migrations, Models, Eloquent y relaciones.
Ya vimos
Hoy partimos de ahí
Pregunta guía
• Migrations para products y categories
• Models Product y Category
• Eloquent: get(), paginate(), create(), update()
• Relaciones belongsTo / hasMany
Vamos a construir el flujo completo de carga y edición de productos: formulario, validación, persistencia y archivo de imagen.
Si ya tenemos tabla y Model, ¿qué falta para que un administrador pueda crear y editar productos sin tocar la base a mano?
Form
Request
Controller
Model
DB
2
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 3

Laravel · Clase 6
Objetivos de la clase
Al terminar, deberían poder completar el alta de productos con validación e imagen.
Formularios Blade
Validación
Storage
Edición segura
2
3
4
1
Construir formularios con @csrf, action, method y campos conectados al Controller.
Definir reglas para nombre, precio, stock, categoría e imagen antes de guardar.
Subir imágenes, guardar rutas en DB y mostrarlas en Blade.
Actualizar datos, mantener o reemplazar imagen y evitar borrar historial.
3
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 4

Laravel · Clase 6
Bloque 1
Del Model al formulario
Route
Controller
Request
Model
5
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 5

Laravel · Clase 6
El recorrido completo de alta
Crear un producto no es una acción única: es un flujo de dos pasos.
GET
/create
create()
formulario
POST
/products
store()
validar
DB
guardar
create()
store()
Muestra el formulario vacío. No modifica la base de datos.
Recibe datos externos, valida, crea el registro y redirige.
Create prepara · Store persiste
6
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 6

Laravel · Clase 6
Resource Controller: el mapa de acciones
Laravel usa nombres convencionales para que el CRUD sea predecible.
Necesidad
Método HTTP
URL
Controller
Listar productos
GET
/admin/products
index()
Lectura
Mostrar formulario de alta
GET
/admin/products/create
create()
El método HTTP comunica la intención: leer, crear, actualizar o borrar.
Guardar producto nuevo
POST
/admin/products
store()
Mostrar formulario de edición
GET
/admin/products/{product}/edit
edit()
Guardar cambios
PUT/PATCH
/admin/products/{product}
update()
Eliminar/desactivar
DELETE
/admin/products/{product}
destroy()
7
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 7

Laravel · Clase 6
Bloque 2
Formularios Blade
@csrf
old()
@error
select
8
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 8

Laravel · Clase 6
Formulario de alta: estructura mínima
El formulario debe saber a qué ruta envía datos y cómo los envía.
action
Blade
<form action="{{ route('admin.products.store') }}"
method="POST"
enctype="multipart/form-data">
@csrf
<input type="text" name="name">
<input type="number" name="price">
<input type="number" name="stock">
<input type="file" name="image">
<button>Guardar producto</button>
</form>
Define la ruta que recibirá el formulario.
method="POST"
Indica que se enviarán datos para crear algo nuevo.
@csrf
Protege el formulario contra envíos no autorizados.
enctype
Es obligatorio si el formulario sube archivos.
9
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 9

Laravel · Clase 6
Campos principales de Product
Cada input debe corresponderse con un dato permitido y validable.
name
Blade
<label>Nombre</label>
<input name="name" value="{{ old('name') }}">
<label>Precio</label>
<input name="price" type="number" step="0.01"
value="{{ old('price') }}">
<label>Stock</label>
<input name="stock" type="number"
value="{{ old('stock', 0) }}">
Texto visible del producto. Debe ser obligatorio y tener longitud máxima.
price
Debe ser numérico y mayor a cero. Para dinero, en DB conviene decimal.
stock
Debe ser entero y no negativo. Stock = 0 no significa error: significa sin disponibilidad.
10
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 10

Laravel · Clase 6
Select de categorías
La categoría no se escribe a mano: se elige desde datos existentes.
PHP
Blade
// ProductController@create
public function create()
{
$categories = Category::where('active', true)
->orderBy('name')
->get();
return view('admin.products.create',
compact('categories')
);
}
<select name="category_id">
<option value="">Elegir categoría</option>
@foreach ($categories as $category)
<option value="{{ $category->id }}">
{{ $category->name }}
</option>
@endforeach
</select>
Controller consulta · Blade presenta
11
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 11

Laravel · Clase 6
old() y @error: feedback para el usuario
Cuando falla la validación, el usuario no debería perder lo que escribió.
old()
Blade
<input name="name"
value="{{ old('name') }}">
@error('name')
<p class="error">{{ $message }}</p>
@enderror
<input name="price"
value="{{ old('price') }}">
@error('price')
<p class="error">{{ $message }}</p>
@enderror
Recupera el valor anterior si el formulario volvió con errores.
@error
Muestra el mensaje de error asociado a un campo.
Criterio UX
El sistema debe ayudar a corregir, no castigar al usuario por equivocarse.
12
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 12

Laravel · Clase 6
Bloque 3
Validación antes de guardar
Request
rules
exists
image
13
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 13

Laravel · Clase 6
Validación de Product
Todo dato que viene del formulario es externo y debe pasar una frontera de seguridad.
PHP
required
$data = $request->validate([
'name' => ['required', 'max:255'],
'description' => ['nullable'],
'price' => ['required', 'numeric', 'min:1'],
'stock' => ['required', 'integer', 'min:0'],
'category_id' => [
'required',
'exists:categories,id'
],
]);
El campo debe venir en el formulario y no puede estar vacío.
numeric / integer
No alcanza con que “parezca” número: Laravel lo valida.
exists
La categoría elegida debe existir realmente en categories.
14
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 14

Laravel · Clase 6
Validar imágenes
Un archivo también es entrada del usuario: debe tener reglas claras.
nullable
PHP
El producto puede crearse sin imagen, si la regla del proyecto lo permite.
$data = $request->validate([
'image' => [
'nullable',
'image',
'mimes:jpg,jpeg,png,webp',
'max:2048'
],
]);
image
Verifica que el archivo sea una imagen válida.
mimes
Limita formatos aceptados: jpg, png, webp, etc.
max
Controla tamaño. 2048 significa 2 MB aproximadamente.
15
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 15

Laravel · Clase 6
Bloque 4
Storage y carga de imágenes
enctype
store
path
public
16
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 16

Laravel · Clase 6
¿Qué pasa cuando subimos una imagen?
La base de datos no necesita guardar el archivo completo: guarda su ruta.
input file
Request
Storage
ruta
DB
Idea central
El archivo vive en storage. La columna image guarda algo como products/remera.webp. Luego Blade usa esa ruta para mostrar la imagen.
17
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 17

Laravel · Clase 6
Guardar imagen y ruta en la base
El Controller procesa el archivo antes de crear el producto.
PHP
hasFile()
public function store(Request $request)
{
$data = $request->validate([...]);
if ($request->hasFile('image')) {
$data['image'] = $request->file('image')
->store('products', 'public');
}
Product::create($data);
return redirect()
->route('admin.products.index');
}
Pregunta si el formulario envió un archivo en el campo image.
store()
Guarda el archivo en el disco configurado.
public
Indica que luego la imagen podrá mostrarse públicamente.
18
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 18

Laravel · Clase 6
Mostrar la imagen en Blade
Para que el navegador vea el archivo, Laravel debe exponer storage públicamente.
Terminal
Blade
# una vez en consola
php artisan storage:link
# crea acceso público a storage/app/public
@if ($product->image)
<img src="{{ asset('storage/' . $product->image) }}"
alt="{{ $product->name }}">
@endif
Atención
Sin storage:link, la ruta puede estar bien guardada pero el navegador no encuentra la imagen.
19
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 19

Laravel · Clase 6
Editar producto: datos actuales + nueva entrada
edit() muestra el formulario cargado; update() procesa cambios.
PHP
Blade
public function edit(Product $product)
{
$categories = Category::orderBy('name')->get();
return view('admin.products.edit', [
'product' => $product,
'categories' => $categories,
]);
}
<input name="name"
value="{{ old('name', $product->name) }}">
<input name="price"
value="{{ old('price', $product->price) }}">
@method('PUT')
@csrf
old(campo, valor actual) permite volver con errores sin perder cambios.
20
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 20

Laravel · Clase 6
Reemplazar imagen sin romper el producto
Si el usuario no sube una nueva imagen, se conserva la anterior.
PHP
Caso A
public function update(Request $request, Product $product)
{
$data = $request->validate([...]);
if ($request->hasFile('image')) {
$data['image'] = $request->file('image')
->store('products', 'public');
}
$product->update($data);
return redirect()
->route('admin.products.index');
}
No sube archivo nuevo: la columna image no se modifica.
Caso B
Sube archivo nuevo: se guarda una nueva ruta y se actualiza el producto.
Mejora futura
Eliminar archivo viejo para no acumular imágenes sin uso.
21
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 21

Laravel · Clase 6
Desactivar antes que borrar
En un e-commerce, el historial de compra suele ser más importante que “limpiar” tablas.
delete()
PHP
// Borrado físico
$product->delete();
// Alternativa para catálogo
$product->update([
'active' => false
]);
Elimina el registro. Puede traer problemas si otros datos dependen de él.
active = false
Oculta del catálogo sin destruir historial ni relaciones.
Regla profesional
Antes de borrar, preguntarse: ¿quién necesita este dato después?
22
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 22

Laravel · Clase 6
Bloque 5
Práctica guiada y cierre
diseño
código
decisiones
23
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 23

Laravel · Clase 6
Mini práctica: diseñar el alta de producto
1. Campos
2. Reglas
3. Imagen
4. Flujo
1
2
3
4
Elegir campos obligatorios y opcionales del formulario Product.
Escribir reglas de validación para esos campos.
Definir si la imagen será obligatoria, formatos y tamaño máximo.
Dibujar create → store y edit → update.
24
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 24

Laravel · Clase 6
Tarea para la próxima clase
Conectar el TP1 con un formulario real de administración.
Armar el formulario Blade de alta de producto.
Incluir name, description, price, stock, category_id e image.
Definir reglas de validación para cada campo.
Escribir el flujo create() y store() en pseudocódigo o Laravel.
Traer una decisión justificada sobre borrar vs desactivar.
PHP
Route::resource(
'admin/products',
ProductController::class
);
// próxima etapa
// auth + middleware + panel protegido
26
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
