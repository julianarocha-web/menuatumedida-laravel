# Portales y E-Commerce _ Clase 0

Total: 43 diapositivas

## Diapositiva 1

Portales y E-Commerce
2026
Clase 0

## Diapositiva 2

Un poco sobre mí…
Desarrollador web freelance desde 2017
Community Manager (desde 2018)
Data Analyst (2020)
Lic. Comunicación Digital (Universidad de Palermo, 2021)
Profesor titular de la materia Emprendimientos Digitales I en Universidad de palermo (2022)
Salesforce Developer Certified (2022)
Profesor titular de Programacion en Nueva Escuela de D y C (2023)
Profesor titular de Marketing Digital en Nueva Escuela de D y C (2024)
Make Certified (2023)
CEO de Molinos digital LINK
Algunos de mis proyectos más importantes fueron:
Universidad Austral LINK
Bliss Buenos Aires LINK
Albano Cozzuol  LINK
Zakon Advisors LINK
Buenos Aires Bureau LINK
St George’s College LINK 1 LINK 2
Camposanto San Marcos LINK
<footer > Joaquín Molinos / Comunicador Digital / PD2</footer>

## Diapositiva 3

Objetivo de la Asignatura
Programar bajo el paradigma de programación orientada a objetos (OOP).Comprender el concepto de Patrones de Diseño, que son y cómo aplicarlos.Producir aplicaciones complejas para monetización y servicios.Integración de API’s (Mercadopago, Paypal,
Google maps, etc)

## Diapositiva 4

Proyecto Integrador
Desarrollo individual de una web dinámica inspirada en un emprendimiento, integrando los lenguajes y herramientas trabajados a lo largo de la carrera e incorporando PHP, MySQL y usando Laravel  como framework principal. Cada estudiante deberá diseñar y programar una aplicación web completa que incluya, como mínimo, una home, una página sobre la película, un formulario de contacto, páginas de login, registro y edición de perfil, un blog temático, una sección de e-commerce con merchandising de la película elegida y un panel de administración.
Además del desarrollo técnico, el proyecto deberá contemplar una etapa previa de investigación y diseño, que incluya el análisis del público objetivo, la definición de los objetivos del sitio, la elaboración de un mapa del sitio, un wireframe en baja fidelidad y una propuesta visual en alta fidelidad. Desde el punto de vista técnico, se espera que el proyecto utilice base de datos en MySQL, autenticación con sesiones, validaciones de formularios, funcionalidades CRUD, manejo de archivos e imágenes, y una estructura responsiva y coherente. La entrega final deberá incluir el proyecto completo, la base de datos, un documento de presentación y una defensa oral.

## Diapositiva 5

Fechas importantes
Parcial → 12/10 TPS aprobados + 50% del proyecto realizado (diseño + frontend basico y funcionalidades básicas ya realizadas).
Recup. parcial → 19/10 TPS aprobados + 50% del proyecto realizado (diseño + y funcionalidades básicas ya realizadas).
Final de cursada → 23/11 TPS aprobados + 100% del proyecto terminado y aprobado.
Terminan las clases → 23/11
Final → Del 30/11 al 14/12  Presentación defendiendo y explicando el proyecto

## Diapositiva 6

¿Cómo nos manejaremos?
Durante la clase 70% teoría 30% práctica

## Diapositiva 7

Arquitectura Cliente - Servidor

## Diapositiva 8

Arquitectura Cliente - Servidor

## Diapositiva 9

Arquitectura Cliente - Servidor

## Diapositiva 10

WEBS ESTÁTICAS
Ausencia de funcionalidades.
El visitante no puede interactuar más allá de los enlaces de la misma.
Realizadas en XHTML o HTML.
Para cambiar los contenidos de la página, es imprescindible acceder al servidor donde está alojada la página. El proceso de actualización es lento, tedioso y esencialmente manual.

## Diapositiva 11

WEBS DINÁMICAS
Nos permiten interactuar con ellas y nos ofrecen funcionalidades realmente sorprendentes: carritos de compra, realizar busquedas en el contenido, participar en foros de discusión, etc.
Se desarrollan con lenguajes que se ejecutan en el servidor (PHP, ASP, etc.), además del HTML.
El proceso de actualización es sumamente sencillo, sin necesidad de entrar en el servidor.

## Diapositiva 12

¿Página web dinámica o estática?
Por el tipo de archivo:
.asp, .php,
página web dinámica.Otros indicadores:
Si el sitio ofrece carrito de compras, buscador, zona restringida para usuarios registrados, etc. estamos ante una página web dinámica.
Por el tipo de archivo:
.html,
página web estática.

## Diapositiva 13

Que necesito?
Necesitamos simular un servidor de pruebas "local" (no conectados a Internet) de nuestras webs.

## Diapositiva 14

Que necesito?
Para esto es necesario instalar varios programas como Apache, Mysql, PHP, PhpMyAdmin.

## Diapositiva 15

Xampp
Programa que instala en un solo paso:
El servidor Apache (es un software (libre) servidor HTTP de código abierto que implementa el protocolo HTTP/1.1 y la noción de sitio virtual.
El módulo para programación en PHP (lenguaje de programación interpretado, diseñado para la creación de páginas web dinámicas. Es usado principalmente en interpretación del lado del servidor.)
La base de datos MySQL (software dedicado a servir de interfaz entre la base de datos, el usuario y las aplicaciones que la utilizan.)

## Diapositiva 16

¿Qué es PHP?
Pre-Hypertext Processor (Procesador Previo al Hipertexto)

## Diapositiva 17

¿Qué es PHP?
Es un lenguaje de programación utilizado para el diseño de sitios web dinámicos, con la posibilidad de combinarlo con bases de datos.
Los script de PHP se intercalan en el código HTML,
el encargado de procesar esos scripts es el intérprete del servidor web.

## Diapositiva 18

Funcionamiento
PHP es un lenguajeejecutado en el servidor
lo que se codifique en PHP, no va a ser interpretado por el Navegador, si no por un Servidor Web que procese esas instrucciones y devuelva al clienteel código HTML resultante.

## Diapositiva 19

Funcionamiento

## Diapositiva 20

Alcances
Logra un mayor dinamismo del contenido
Combinándolo con la gestión de bases de datos, lograremos hacer :
Foros
Guestbooks
Blogs
Calendarios
Sistemas de Carrito de Compras
Restricción de acceso a sitios web mediante user/password
Registro de estadísticas
Proceso de Formularios de Email

## Diapositiva 21

Condiciones
Nuestros archivos deben llevar la extensión .php para que el intérprete los procese.
Debemos acceder a estos archivos PHP a través de un servidor web
Se pueden intercalar órdenes en lenguaje PHP alternándolas dentro de una página escrita en lenguaje HTML, tantas veces como sea necesario

## Diapositiva 22

Etiquetas de apertura y cierre
<? ?> Muy usado, pero no es estándar. No todas las configuraciones del intérprete de php habilitan su uso.
<?php ?> El más universal: funciona siempre.
<script language="php"> </script> Se utiliza para versiones antiguas de FrontPage como editor.
<% %> Estilo ASP, no es estándar, depende de la configuración del intérprete.

## Diapositiva 23

Sintaxis PHP
Las sentencias siempre finalizan con un punto y coma;
El código PHP siempre debe estar encerrado por sus delimitadores ( <?php y ?> )
Los comentarios de una sola línea en PHP pueden hacerse empezando la línea con // o con #
Los comentarios de múltiples líneas están encerrados entre /* y  */

## Diapositiva 24

Sintaxis PHP
Para agregar un código PHP dentro de una página HTML debemos, al crear el archivo, definirlo con extensión php y dentro del contenido de la página, encerrar el programa entre los símbolos <?php [script PHP] ?>.
<html>
<head>
</head>
<body>
<?php
echo "Hola Mundo";
?>
</body>
</html>

## Diapositiva 25

Variables
Una variable en PHP es un espacio de memoria que guarda un valor, y tiene un identificador (nombre de la variable) y un tipo de dato.
No es necesario indicar de qué tipo de dato es una variable, el intérprete de PHP se dara cuenta según su contenido.
Se utiliza para guardar información en ellas que luego usaremos varias veces a lo largo del código de la página.

## Diapositiva 26

Variables
Los nombres de variables comienzan con el signo $ y son sensibles a mayúsculas y minúsculas.
Las variables se declaran cuando se le asigna un valor, por ejemplo:
$dia = 24; //Se declara una variable de tipo integer.
$sueldo = 758.43; //Se declara una variable de tipo double.
$nombre = "juan"; //Se declara una variable de tipo string (cadena).
$salida = true; //Se declara una variable boolean.

## Diapositiva 27

Sintaxis de las variables
Las variables SIEMPRE llevan el signo $ (pesos) adelante.
Los únicos caracteres que aceptan los nombres de las variables son letras, números y el guión bajo
Los nombres de las variables NUNCA pueden llevar espacios ni empezar con un número
Los nombres de las variables pueden empezar con letras o con un guión bajo
Los nombres de las variables son sensibles a mayúsculas y minúsculas

## Diapositiva 28

Variables – usos válidos e inválidos
Nombres válidos
Nombres no válidos
$1
$666
$-variable
$2variable
$ variable
$’variable
$>variable
$<variable
$variable
$var1
$var_1
$VAR
$vAR
$_variable

## Diapositiva 29

Ejemplos:
<?php
$Cadena = "Hola Mundo"; //Esto es correcto
$cadena = "Hola Mundo"; /*	Esto también es correcto pero
NO es lo mismo que la línea 					anterior.
$Cadena y $cadena son dos 					variables distintas */
$_Cadena = "Hola Mundo"; //Esto es correcto
$1Cadena = "Hola Mundo"; //Esto NO es correcto
$Una Cadena = "Hola Mundo"; //Esto TAMPOCO es correcto.
?>

## Diapositiva 30

Operando con Variables
Operadores aritméticos
+  	Suma dos valores
- 	Resta dos valores (o pasa a negativo un valor)
* 	Multiplica dos valores
/	 Divide dos valores
% 	Resto de dividir dos valores

## Diapositiva 31

Operando con Variables
Operadores de comparación
==  	Comprueba si dos números son iguales
!= 	Comprueba si dos números son distintos
> 	Mayor que, devuelve true en caso afirmativo
< 	Menor que, devuelve true en caso afirmativo
>= 	Mayor o igual
<= 	Menor o igual

## Diapositiva 32

Operando con Variables
Operadores lógicos!  	Operador NO o negacion.Si era true pasa a false y viceversa
And Operador Y, si ambos son verdaderos vale verdadero
or 	Operador O, vale verdadero si alguno de los dos es verdadero
xor 	Verdadero si alguno de los dos es true pero nunca ambos
&& 	True si ambos lo son
|| 	True si alguno lo es

## Diapositiva 33

Operando con Variables
Operadores de incremento
++ $variable 	Aumenta de 1 el valor de $variable
-- $variable      Reduce de uno el valor de $variable

## Diapositiva 34

Operando con Variables
Operadores lógicos=  	Asigna a la parte derecha el valor izquierdo
+= 	Realiza la suma de la derecha con la izquierda y la asigna a la derecha
-= 	Realiza la resta de la derecha con la izquierda y la asigna a la derecha
*= 	Realiza la multiplicación de la derecha con la izquierda y la asigna a la derecha
/= 	Realiza la división de la derecha con la izquierda y la asigna a la derecha
%= 	Se obtiene el resto y se asigna

## Diapositiva 35

Variables numéricas Almacenan cifras |  | 
Enteros => | $entero=2002; => | Numeros sin decimales
Real => | $real=3.14159; => | Numeros con o sin decimal

## Diapositiva 36

Array
También llamado “Vector”, un Array es una colección de valores.
Se utiliza el delimitador [ ] para acceder a los diferentes elementos del vector (índices).
$dias=array(31,28,31,30);
$dias[0]=31;
$dias[1]=28;
$dias[2]=31;
$dias[3]=30;

## Diapositiva 37

Array Asociativo
Un array asociativo permite acceder a un elemento del vector por medio de un subíndice de tipo string.
<?php
$registro['dni']="20456322";
$registro['nombre']="Martinez Pablo";
$registro['direccion']="Colon 134";
echo $registro['nombre'];
?>
<?php
$registro=array('dni'=>'20456322',
'nombre'=>'Martinez Pablo',
'direccion'=>'Colon 134');
echo $registro['dni'];
?>

## Diapositiva 38

Condicionales If
Cuando se pretende que el script tome un camino concreto en determinados casos y otro diferente si las condiciones de ejecución difieren, se utiliza el conjunto de instrucciones: if, else y elseif.
La estructura base es la siguiente:
if (Condición) {  Instrucción 1;  Instrucción 2;}else {  Instrucción a;  Instrucción b;}

## Diapositiva 39

Condicionales If
if (Condición) {  Instrucción 1;else {  Instrucción 2;}
Si la condición es verdadera se ejecutará la instrucción 1,si es falsa se ejecutará la instrucción 2

## Diapositiva 40

Condicionales If
Otra forma de sintaxis:
$variable = condicion ? Valor si es verdadera : valor si es falsa;
Ejemplo:
<?php
$tmp = 1;
$variable = $tmp>5 ? 'Es mayor a 5' : No es mayor a 5';
echo $variable;
?>

## Diapositiva 41

Condicionales elseIf
Si la Condición1 no se cumple, pasa a la Condición2, si es verdadera se ejecutará la instrucción b,si es falsa se ejecutará la instrucción c.
Si se cumple la Condición 1, se ejecutará la Instrucción a.
if (Condicion 1)
{
Instrucción a;
}
elseif (Condicion 2)
{
Instrucción b;
}
else
{
Instrucción c;
}
Recordar los operadores:
== igual.
!= distinto.
>= mayor o igual.
> mayor.
<= menor o igual
< menor

## Diapositiva 42

Condicional Switch-case
Un switch busca dentro de los “case” el valor de la variable o expresión evaluada (generalmente se evalúan variables).
Si lo encuentra, ejecuta el código correspondiente.
Si no lo encuentra, ejecuta el código por default.
Observaciones: El case por default es opcional.
Es importante poner “break;”  al final de cada bloque de código dentro de cada case para que el switch no siga comparando al valor de la variable con los case que le siguen al correcto.

## Diapositiva 43

Condicional Switch-case
Sintaxis:
switch(expresión) {
case "a": //Código a ejecutar;
break;
case "b": //Código a ejecutar;
break;
case "c": //Código a ejecutar;
break;
default: //Código a ejecutar por default.
}
