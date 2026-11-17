# Clase_7_Laravel_Introduccion_Mercado_Pago_Checkout_Pro_Nueva_Escuela

Total: 35 diapositivas

## Diapositiva 1

Laravel · Clase 7
Mercado Pago en Laravel
De la tienda al checkout
{ }
Introducción, arquitectura y configuración del entorno de prueba
$  ARS
E-commerce
Checkout Pro
Orders API
Sandbox
API  →  Pago
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web

## Diapositiva 2

LARAVEL · CLASE 7
Desde la clase 6: ¿qué tenemos y qué falta?
Retomamos el mismo e-commerce: productos, formulario, validación y base de datos.
Ya construimos
Tenemos que sumar
Pregunta…
• Productos y categorías
• Alta/edición con Blade
• Validación de campos
• Imagen en Storage
• Carrito y pedido
• Total definitivo
• Inicio del pago
• Confirmación del resultado
Si el cliente hace clic en “Pagar”, ¿qué debe hacer Laravel y qué debe hacer Mercado Pago?
Hoy configuramos y entendemos el circuito. Los cobros reales quedan para la próxima etapa.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
2

## Diapositiva 3

LARAVEL · CLASE 7
Bloque 1
Del e-commerce al cobro
La pasarela complementa el checkout; no reemplaza los datos de nuestra tienda.
Carrito
Pedido
Pago
Confirmación
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
5

## Diapositiva 4

LARAVEL · CLASE 7
¿Qué es una pasarela de pagos?
Servicio que permite iniciar y gestionar pagos mediante un proveedor especializado.
Nuestra tienda · Laravel
Mercado Pago · Proveedor
Construye el carrito, valida los productos, calcula el total, registra el pedido y gestiona el stock.
No debería capturar ni almacenar números de tarjeta.
Ofrece la experiencia de pago, procesa transacciones y comunica cambios de estado.
El resultado debe verificarse de forma segura antes de entregar el pedido.
La tienda vende y registra el pedido; la plataforma de pagos procesa la transacción.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
6

## Diapositiva 5

LARAVEL · CLASE 7
Tres conceptos que NO son lo mismo
La palabra “orden” puede aparecer tanto en nuestra base como en la API de pagos.
Product
Order (Laravel)
Order (Mercado Pago)
Un ítem disponible para vender: nombre, precio, categoría, stock.
Pedido local: comprador, items, total y estado del proceso de compra.
Recurso del proveedor que prepara el pago y devuelve checkout_url.
Relación recomendada: orders.id local ↔ external_reference enviada a Mercado Pago.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
7

## Diapositiva 6

LARAVEL · CLASE 7
El circuito completo del pago
Esquema conceptual: una operación debe poder rastrearse de principio a fin.
Carrito
Pedido local
Laravel valida
Crear order MP
→
→
→
1
2
3
4
checkout_url
Usuario paga
Notificación
Verificar y actualizar
→
→
→
5
6
7
8
Crear la order externa NO significa que el pago ya esté aprobado.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
8

## Diapositiva 7

LARAVEL · CLASE 7
Estados de pago: ¿qué mostraríamos?
Los estados concretos se consultarán en la API; estos son escenarios comerciales para comprender el flujo.
Aprobado
Pendiente
Rechazado / cancelado
Confirmación verificada: pedido marcado como pagado y comienza preparación/envío.
Aún no está confirmado: se informa al cliente y se espera una actualización.
El pedido permanece sin pagar. Se habilita reintentar de forma segura.
Nunca descontar stock o despachar solo porque el navegador volvió a /pago/exito.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
9

## Diapositiva 8

LARAVEL · CLASE 7
Mini práctica 1 · Ordená el proceso
Trabajo en parejas · 4 minutos + 1 minuto de corrección oral.
Ordenen estos eventos sin mirar la slide anterior
A. Mercado Pago devuelve un enlace checkout_url.
B. Laravel verifica productos, cantidades y total.
C. El servidor confirma el resultado y actualiza el pedido.
D. El usuario completa el checkout externo.
E. Laravel registra el pedido local.
Secuencia esperada: B → E → A → D → C.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
10

## Diapositiva 9

LARAVEL · CLASE 7
Bloque 2
Elegir cómo integrar Mercado Pago
Para este grupo conviene empezar con un checkout hospedado y luego profundizar.
Checkout Pro
Orders API
Redirección
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
11

## Diapositiva 10

LARAVEL · CLASE 7
¿Qué producto de Mercado Pago conviene usar?
Las soluciones varían según cuánta experiencia de pago queramos construir dentro de nuestro sitio.
Solución
Experiencia del comprador
Complejidad para empezar
Checkout Pro
Se redirige a Mercado Pago para pagar
Más accesible para este curso
Checkout Bricks
Componentes de pago embebidos en nuestra web
Mayor configuración y front-end
Checkout API
Checkout más personalizado dentro del sitio
Mayor responsabilidad técnica
Elegimos Checkout Pro para priorizar Laravel, pedidos, seguridad y estados antes que UI de tarjetas.
Fuente: Mercado Pago Developers · Primeros pasos
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
12

## Diapositiva 11

LARAVEL · CLASE 7
Checkout Pro: experiencia del comprador
Laravel inicia la operación; el usuario paga en un entorno de Mercado Pago.
Nuestra tienda
Servidor Laravel
Mercado Pago
→
→
Producto + carrito
Botón “Pagar”
Crea recurso de pago
Obtiene checkout_url
Muestra la pantalla de pago
Procesa la operación
Ventaja didáctica: no programamos el formulario que recibe datos sensibles de tarjeta.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
13

## Diapositiva 12

LARAVEL · CLASE 7
Actualización 2026: Orders API vs Preferences API
Mercado Pago mantiene ambos caminos para Checkout Pro, pero recomienda Orders API en integraciones nuevas.
Orders API · camino recomendado
Preferences API · camino clásico
Creamos una “order” mediante POST /v1/orders. La respuesta incluye checkout_url para redirigir al usuario.
Crea una “preference” y devuelve init_point. Aparecerá en tutoriales existentes, pero no será el recorrido principal.
En la próxima clase implementaremos el flujo moderno por Orders API.
Fuente: Documentación oficial · Referencia de Checkout Pro (consultada 26/10/2026)
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
14

## Diapositiva 13

LARAVEL · CLASE 7
¿Qué información enviaremos a Mercado Pago?
Vista didáctica del cuerpo de una petición a la API moderna de Checkout Pro.
POST /v1/orders
Lectura del ejemplo
{
"type": "online",
"processing_mode": "manual",
"total_amount": "50000.00",
"external_reference": "pedido-42",
"items": [
{ "title": "Remera", "unit_price": "25000.00", "quantity": 2 }
]
}
• type: compra online
• processing_mode: manual
• total_amount: total calculado
• external_reference: nuestro pedido
• items: detalle vendido
Fuente: Docs Checkout Pro · Crear y configurar una order
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
15

## Diapositiva 14

LARAVEL · CLASE 7
Dos URLs, dos responsabilidades distintas
Una hace navegar al comprador; la otra comunica un evento al servidor.
URL de retorno
Webhook
El navegador vuelve a nuestro sitio para mostrar una pantalla de estado.
Sirve para experiencia del usuario.
Mercado Pago envía una notificación al backend. Nuestro servidor valida autenticidad y consulta el estado antes de actuar.
Sirve para sincronización segura.
Retorno visual ≠ confirmación confiable del pago.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
16

## Diapositiva 15

LARAVEL · CLASE 7
Bloque 3
Configurar el ambiente de pruebas
Hoy sí abrimos Developers y dejamos cada e-commerce preparado para integrar.
Aplicación
Credenciales
Test
Laravel
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
17

## Diapositiva 16

LARAVEL · CLASE 7
Antes de abrir Developers: checklist
El proceso puede variar por permisos, país y estado de la cuenta; no exigir una cuenta propia a quien no pueda crearla.
Acceso a Mercado Pago y al panel de integraciones; tener en cuenta requisitos de edad.
Cuenta habilitada
1
Código local funcionando, products/categories y posibilidad de crear orders.
Proyecto Laravel
2
Usaremos credenciales y usuarios de prueba; nunca tarjetas reales.
Entorno de pruebas
3
Será necesaria en una fase posterior para retornos y webhooks. No alcanza localhost.
URL pública HTTPS
4
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
18

## Diapositiva 17

LARAVEL · CLASE 7
Demo guiada · Crear la aplicación
Mostrar el flujo en pantalla (la interfaz puede cambiar de ubicación y nombres).
Entrar a Developers
Ir a Tus integraciones
1
2
developers.mercadopago.com.ar
Abrir panel y elegir Crear aplicación
Identificar el producto
Guardar y abrir la app
3
4
Seleccionar Checkout Pro (Orders API) cuando esté disponible
Revisar datos de integración y entorno Prueba
Si la cuenta no permite crear aplicaciones: observar demo y registrar los pasos.
Fuente: Mercado Pago Developers · Crear aplicación; Tus integraciones
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
19

## Diapositiva 18

LARAVEL · CLASE 7
Prueba y producción: separar desde el día 1
Dos entornos, dos finalidades. Una configuración de prueba no debería cobrar dinero real.
ENTORNO PRUEBA
ENTORNO PRODUCCIÓN
• Simular situaciones de pago
• Trabajar con credenciales de prueba
• Usar compradores y tarjetas de prueba
• Verificar errores sin dinero real
• Aceptar compras reales
• Usar credenciales productivas
• Exigir pruebas completas y permisos
• Controlar notificaciones y seguridad
En esta materia: preparar y probar primero. No activar producción durante la práctica.
Fuente: Mercado Pago Developers · Credenciales
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
20

## Diapositiva 19

LARAVEL · CLASE 7
Public Key y Access Token: no cumplen el mismo rol
La clave privada permite operar con la API desde el servidor; jamás se publica en Blade o JavaScript.
Public Key
Access Token
Clave pública. Según el producto de pago, puede utilizarse en el front-end.
No reemplaza la autorización del backend.
Credencial privada para autenticar solicitudes a la API de Mercado Pago.
Solo backend / variables de entorno. No se comparte ni sube a GitHub.
Nunca pegar credenciales verdaderas en una presentación, tarea o captura de pantalla.
Fuente: Mercado Pago Developers · Credenciales de prueba
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
21

## Diapositiva 20

LARAVEL · CLASE 7
Preparar Laravel: secretos fuera del código
Usamos .env y el archivo config/services.php para centralizar la configuración.
.env · VALORES FICTICIOS
config/services.php
MP_ACCESS_TOKEN=REEMPLAZAR_CON_TOKEN_DE_PRUEBA
MP_PUBLIC_KEY=REEMPLAZAR_CON_CLAVE_PUBLICA
'mercadopago' => [
'access_token' => env('MP_ACCESS_TOKEN'),
'public_key'  => env('MP_PUBLIC_KEY'),
],
// Backend:
config('services.mercadopago.access_token');
El archivo .env real NO debe publicarse en repositorios ni adjuntarse al TP.
Fuente: Buenas prácticas de credenciales + convención Laravel
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
22

## Diapositiva 21

LARAVEL · CLASE 7
Cuentas de prueba y compras simuladas
Para simular una compra se diferencian roles vendedor y comprador; no usar tarjetas reales.
Vendedor
Comprador
Tarjeta de prueba
Es quien integra el checkout y recibe la venta simulada. La aplicación tendrá datos de prueba asociados.
Cuenta de prueba distinta de la vendedora para realizar una simulación de compra.
Datos ficticios provistos por la plataforma para simular aprobaciones, pendientes y rechazos.
Comprador y vendedor de prueba deben operar en el mismo país.
Fuente: Mercado Pago Developers · Cuentas de prueba
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
23

## Diapositiva 22

LARAVEL · CLASE 7
¿Por qué localhost no es suficiente?
La aplicación local es útil para programar; una notificación externa requiere una URL accesible.
localhost:8000
https://...
En nuestra computadora: ideal para aprender, revisar rutas, Models y formularios.
Mercado Pago NO puede enviar un webhook a tu localhost de forma directa.
Un hosting de pruebas o túnel HTTPS temporal puede exponer endpoints para integración.
No publicar credenciales ni datos sensibles por error.
Las URLs de retorno y de webhooks deberán configurarse según los requisitos HTTPS de la plataforma.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
24

## Diapositiva 23

LARAVEL · CLASE 7
Checklist de salida del panel Developers
Antes de empezar a programar el pago, deberíamos reconocer lo siguiente:
Identificamos el proyecto en “Tus integraciones”.
Aplicación creada
✓
Sabemos distinguir Prueba de Producción.
Entorno prueba elegido
✓
Conocemos dónde verlas, sin difundirlas.
Credenciales ubicadas
✓
Entendemos cómo simular compra de prueba.
Cuenta compradora
✓
Tenemos un lugar seguro para configurarlas en Laravel.
Proyecto listo
✓
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
25

## Diapositiva 24

LARAVEL · CLASE 7
Bloque 4
Diseñar la integración en Laravel
Ya conocen MVC: ahora asignamos correctamente nuevas responsabilidades.
Routes
Controller
Order
Webhook
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
26

## Diapositiva 25

LARAVEL · CLASE 7
Mapa MVC del checkout
El proveedor de pagos es una integración externa, no una View ni un Model de nuestro catálogo.
Route
CheckoutController
Order (Eloquent)
MercadoPagoService
Mercado Pago
→
→
→
→
POST /checkout
Valida y coordina
Pedido local
Consume API
checkout_url
Controller orquesta; Order registra la compra; servicio externo integra con la API.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
27

## Diapositiva 26

LARAVEL · CLASE 7
Tabla orders: qué conviene guardar localmente
Revisamos el modelo de datos para no confundir “orden de pago” con “pedido de compra”.
Campo
Qué significa
Ejemplo ficticio
id
Identificador de pedido local
42
user_id
Usuario que compra
8
total
Total recalculado en servidor
50000.00
status
Estado interno del pedido
pending_payment
mp_order_id
ID externo retornado por Mercado Pago
ORD...
created_at
Fecha de creación
timestamp
order_items conserva cantidad y precio unitario al momento de la compra.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
28

## Diapositiva 27

LARAVEL · CLASE 7
Primer mapa de rutas: todavía no cobramos
Se trata de una propuesta para pensar responsabilidades en Laravel, no de un código de integración completo.
¿Y el webhook?
routes/web.php · PROPUESTA
// Iniciar checkout desde la tienda
Route::post('/checkout',
[CheckoutController::class, 'start'])
->name('checkout.start');
// Mostrar un resultado al usuario
Route::get('/checkout/resultado',
[CheckoutController::class, 'result'])
->name('checkout.result');
Será una ruta POST pública al proveedor, sin sesión de usuario, protegida con validación de firma y verificación de datos.
No debe ser igual a result().
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
29

## Diapositiva 28

LARAVEL · CLASE 7
Pseudocódigo: ¿qué hace start()?
Todavía NO es la implementación final; muestra el orden de decisiones que la clase 8 programará.
CheckoutController@start · IDEA
Preguntas de diseño
1. Leer carrito del usuario
2. Recuperar precios y stock desde MySQL
3. Recalcular total de forma segura
4. Registrar pedido local como pending_payment
5. Enviar order al API de Mercado Pago
6. Guardar identificador externo
7. Redirigir al checkout_url obtenido
¿Qué pasa si el precio cambió?
¿Qué pasa si Mercado Pago está caído?
¿Cómo evito iniciar dos pagos idénticos?
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
30

## Diapositiva 29

LARAVEL · CLASE 7
Caso práctico: una compra de dos remeras
El alumno debe poder reconstruir la información sin ver todavía el SDK de pagos.
Datos del carrito
Nuestro pedido
Solicitud externa
Producto: Remera básica
Precio DB: $25.000
Cantidad: 2
Stock disponible: 7
Order #42
OrderItem: 2 × 25.000
Total: $50.000
Estado: pending_payment
external_reference: pedido-42
total_amount: "50000.00"
item: Remera × 2
Devuelve checkout_url
Si alguien manipula el precio enviado desde el navegador, el total se calcula igual desde MySQL.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
31

## Diapositiva 30

LARAVEL · CLASE 7
Cinco reglas de seguridad desde el comienzo
Los pagos son un proceso crítico: la correcta arquitectura es tan importante como el botón.
Token privado solo en backend
1
Total calculado en servidor, no confiado al cliente
2
Verificar notificaciones y estado con la API
3
Usar idempotencia para evitar operaciones duplicadas
4
No entregar productos por la sola URL de retorno
5
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
32

## Diapositiva 31

LARAVEL · CLASE 7
Mini práctica 2 · Diseñá el flujo de tu tienda
Individual o parejas · 7 minutos. Sin necesidad de credenciales ni API conectada.
Consigna para el Proyecto Integrador
Se valora que puedan explicar
1. Elegí un producto y cantidad de compra.
2. Calculá un pedido local (order + order_items).
3. Escribí qué campos enviarías a Mercado Pago.
4. Indicá qué ocurre con pago aprobado, pendiente y rechazado.
5. Decidí qué Controller y Model participan.
• De dónde sale el precio
• Cuándo nace el pedido
• Para qué sirve checkout_url
• Por qué hay que verificar pago
• Qué haría el webhook
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
33

## Diapositiva 32

LARAVEL · CLASE 7
Ticket de salida · 5 preguntas
Responder oralmente o por escrito en los últimos minutos.
¿Qué ventaja tiene Checkout Pro frente a construir el formulario de tarjeta?
1
¿Cuál es la diferencia entre order local y order de Mercado Pago?
2
¿Dónde debe guardarse el Access Token y por qué?
3
¿Para qué sirve checkout_url?
4
¿Por qué no alcanza con recibir al usuario en /checkout/resultado?
5
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
34

## Diapositiva 33

LARAVEL · CLASE 7
Tarea para la clase 8 · Preparar la integración
Entrega corta: todavía NO deben procesar pagos reales ni entregar credenciales.
Entregar un PDF o captura sin secretos
Próxima clase
1. Tema de su e-commerce y producto de prueba.
2. Diagrama de flujo pedido → Mercado Pago → confirmación.
3. Tabla orders preliminar (campos y estados).
4. Rutas propuestas para inicio, resultado y webhook.
5. Checklist: aplicación de prueba creada u observación de demo docente.
Implementar llamada a Orders API con credenciales de prueba, obtener checkout_url y simular compras.
Después: webhooks y validación de confirmación.
Prohibido adjuntar .env, Access Token, datos de tarjeta o cuentas personales.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
35

## Diapositiva 34

LARAVEL · CLASE 7
Recursos oficiales para seguir en clase
Referencias del proveedor para evitar tutoriales desactualizados.
Primeros pasos
https://www.mercadopago.com.ar/developers/es/docs/getting-started
Checkout Pro · Orders API
https://www.mercadopago.com.ar/developers/es/docs/checkout-pro-orders/create-order
Crear aplicación
https://www.mercadopago.com.ar/developers/es/docs/checkout-pro-orders/create-application
Cuentas de prueba
https://www.mercadopago.com.ar/developers/es/docs/checkout-pro-preferences/test-accounts
Notificaciones de pago
https://www.mercadopago.com.ar/developers/es/docs/checkout-pro-orders/notifications
Usar las páginas actuales del proveedor; el flujo clásico de Preferences sigue existiendo.
Fuente: Documentación de Mercado Pago Developers, consultada 26/10/2026
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
36

## Diapositiva 35

LARAVEL · CLASE 7
Un checkout no es
solo un botón de pagar.
Es un proceso que conecta pedidos, validaciones, una API externa y confirmación segura.
Próximo paso: conectar la Orders API en ambiente de prueba.
Nueva Escuela de Diseño y Comunicación · Diseño y Programación Web
