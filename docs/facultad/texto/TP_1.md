# TP Portales y Comercio Electrónico

Trabajo Práctico N.º 1
Diseño inicial del e-commerce
Materia: Portales y Comercio Electrónico / LaravelProyecto Integrador: Desarrollo de un e-commerce funcionalModalidad: Individual
Objetivo
El objetivo de este primer trabajo práctico es comenzar a definir las bases del e-commerce que se desarrollará progresivamente durante la cursada.
En esta instancia se trabajará sobre los conceptos iniciales vistos en clase: PHP, MySQL, modelado de datos y Programación Orientada a Objetos (POO).
No se busca desarrollar todavía el sitio completo, sino comenzar a pensar cómo estará estructurado el sistema, qué información deberá almacenar y cómo representar uno de sus elementos principales mediante una clase en PHP.
Consigna
Cada estudiante deberá definir una propuesta inicial para su e-commerce y resolver los siguientes puntos.
1. Definición de la temática del e-commerce
Elegir qué tipo de productos comercializará el e-commerce.
Incluir una breve descripción donde se explique:
Qué productos venderá.
A qué público estará dirigido.
Al menos 3 ejemplos de productos que podrían formar parte del catálogo.
Algunos ejemplos posibles de temática son: indumentaria, tecnología, libros, productos deportivos, cosmética, decoración, alimentos, accesorios, productos para mascotas, entre otros.
La temática elegida deberá ser lo suficientemente clara como para poder continuar desarrollándola durante el resto de la cursada.

2. Definición de la tabla products
Pensando en la base de datos del proyecto, definir al menos 8 campos posibles para la tabla products.
Para cada campo indicar:
Nombre del campo.
Qué información almacenará.
Como mínimo deberán contemplarse datos relacionados con:
Identificación del producto.
Nombre.
Precio.
Stock.
Ejemplo:
Campo | Información que almacena
id | Identificador único del producto
name | Nombre del producto
description | Descripción
price | Precio
stock | Cantidad disponible
category_id | Categoría a la que pertenece
image | Imagen principal
active | Indica si el producto está publicado

El ejemplo es orientativo. Cada estudiante deberá adaptar los campos a las necesidades particulares de su proyecto.

3. Definición de tablas relacionadas
Además de products, proponer al menos 3 tablas adicionales que sean necesarias para el funcionamiento del e-commerce.
Por ejemplo:
categories
users
orders
order_items
Para cada tabla deberán indicar:
Qué información representa.
Cómo se relaciona con las demás tablas.
No alcanza con enumerar los nombres. Es necesario explicar brevemente las relaciones.
Por ejemplo:
categories: almacena las categorías disponibles en el e-commerce.
Relación:
Una categoría puede tener muchos productos y cada producto pertenece a una categoría.
Otro ejemplo:
Un usuario puede realizar muchos pedidos y cada pedido pertenece a un usuario.
El objetivo es comenzar a comprender que la información de una aplicación no se almacena toda dentro de una única tabla.

4. Creación de la clase Product
Crear en PHP una clase llamada:
Product
La clase deberá contener como mínimo las siguientes propiedades:
name
price
stock
También deberá incluir un constructor que permita cargar esos datos al crear un nuevo producto.
Ejemplo:
<?php

class Product
{
public function __construct(
public string $name,
public float $price,
public int $stock
) {}
}

5. Método hasStock()
Dentro de la clase Product, crear un método llamado:
hasStock()
El método deberá verificar si el producto posee stock disponible.
Debe devolver:
true si el stock es mayor a 0.
false si el stock es igual a 0.
Ejemplo:
public function hasStock(): bool
{
return $this->stock > 0;
}
La clase completa podría tener inicialmente esta estructura:
<?php

class Product
{
public function __construct(
public string $name,
public float $price,
public int $stock
) {}

public function hasStock(): bool
{
return $this->stock > 0;
}
}

6. Crear un objeto de prueba
Una vez creada la clase, deberán crear al menos una instancia de Product.
Por ejemplo:
$product = new Product(
"Remera Oversize",
25000,
10
);
Luego deberán ejecutar el método:
var_dump($product->hasStock());
En este caso, el resultado debería ser:
bool(true)
También pueden probar qué sucede cuando crean un producto con stock igual a 0.
Entrega
La entrega deberá incluir:
Descripción de la temática elegida.
Público al que estará dirigido el e-commerce.
3 ejemplos de productos.
Los 8 campos propuestos para la tabla products.
Las 3 tablas relacionadas y la explicación de sus relaciones.
Código de la clase Product.
Constructor.
Método hasStock().
Al menos una instancia de prueba de la clase.
Archivos a entregar
1. Documento PDF, incluyendo la definición conceptual y el modelado inicial de la base de datos.
2. Archivo PHP, denominado preferentemente:
Product.php
con la clase desarrollada y las pruebas realizadas.
Criterios de evaluación
Se tendrá en cuenta:
Claridad y coherencia de la temática elegida.
Correcta identificación de la información necesaria para un producto.
Coherencia de las tablas propuestas.
Comprensión de las relaciones entre tablas.
Correcta utilización de clases, propiedades, objetos y métodos.
Correcta implementación del constructor.
Correcto funcionamiento del método hasStock().
Orden y claridad del código.
Capacidad para justificar las decisiones tomadas.


Importante
Este trabajo práctico constituye el primer avance del Proyecto Integrador de la materia.
Las decisiones tomadas en esta instancia serán retomadas durante las próximas clases cuando trabajemos con:
Laravel → Migrations → Models → Eloquent → Controllers → Views → CRUD
Por este motivo, la propuesta elegida debe ser pensada como un proyecto que continuará creciendo durante el cuatrimestre.
