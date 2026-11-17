<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Plato;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menu = [
            'Desayunos' => [
                ['nombre' => 'Pancakes', 'descripcion' => 'Pancakes acompañados con frutas de estación y jugo exprimido de naranja', 'precio' => 13900, 'imagen' => 'img/comidas/pancakes.webp'],
                ['nombre' => 'Tostadas', 'descripcion' => 'Tostadas de pan blanco con dos huevos fritos y toques de ricas especias.', 'precio' => 13900, 'imagen' => 'img/comidas/huevos.webp'],
                ['nombre' => 'Croissant', 'descripcion' => 'Croissant XL de manteca, rellena de nutella, crema pastelera o chocolate derretido.', 'precio' => 13900, 'imagen' => 'img/comidas/pan-chocolate.webp'],
                ['nombre' => 'Bowl', 'descripcion' => 'Bowl relleno de un mix de yogurt, frutas, frutos secos y granola.', 'precio' => 13900, 'imagen' => 'img/comidas/granola.webp'],
            ],
            'Almuerzos' => [
                ['nombre' => 'Milanesa', 'descripcion' => 'Milanesa de pollo acompañada con salsa a elección y papas fritas.', 'precio' => 13900, 'imagen' => 'img/comidas/milanesa.webp'],
                ['nombre' => 'Bruschettas', 'descripcion' => 'Bruschettas de carne, pan, pollo y verduras a elección Acompañadas de arroz.', 'precio' => 13900, 'imagen' => 'img/comidas/bruschetas.webp'],
                ['nombre' => 'Pasta liviana', 'descripcion' => 'Pasta (fideos a eleccion) salteada con verduras de estación y semillas.', 'precio' => 13900, 'imagen' => 'img/comidas/pasta-almuerzo.webp'],
                ['nombre' => 'Pizza', 'descripcion' => 'Pizza individual mediterranea, napolitana, muzzarella o 4 quesos.', 'precio' => 13900, 'imagen' => 'img/comidas/pizza.webp'],
            ],
            'Meriendas' => [
                ['nombre' => 'Cheesecake', 'descripcion' => 'Cheesecake cubierto con mermelada de frutos rojos casera y fresca', 'precio' => 13900, 'imagen' => 'img/comidas/cheesecake.webp'],
                ['nombre' => 'Waffles', 'descripcion' => 'Waffles caseros cubiertos con dip a eleccion (dulce de leche, nutella o crema)', 'precio' => 13900, 'imagen' => 'img/comidas/waffles.webp'],
                ['nombre' => 'Cookies', 'descripcion' => 'Porcion de 4 cookies medianas de vainilla, chocolate, pistacho o red velvet.', 'precio' => 13900, 'imagen' => 'img/comidas/cookies.webp'],
                ['nombre' => 'Torta apple', 'descripcion' => 'Pie relleno de manzana caramelizada y crema pastelera. Porcion individual o grande.', 'precio' => 13900, 'imagen' => 'img/comidas/torta-manzana.webp'],
            ],
            'Cenas' => [
                ['nombre' => 'Burger', 'descripcion' => 'Hamburguesa completa acompañado con papas fritas y un dip a eleccion', 'precio' => 13900, 'imagen' => 'img/comidas/burger.webp'],
                ['nombre' => 'Pastas', 'descripcion' => 'Pastas a eleccion acompañadas con salsa de crema, salsa bolognesa o pesto.', 'precio' => 13900, 'imagen' => 'img/comidas/pasta.webp'],
                ['nombre' => 'Arroz proteico', 'descripcion' => 'Arroz azafranado con pedazos de muslo de pollo salteados a la crema.', 'precio' => 13900, 'imagen' => 'img/comidas/paella.webp'],
                ['nombre' => 'Chow fan', 'descripcion' => 'Arroz chino frito salteado con verduras y langostinos crispy o naturales.', 'precio' => 13900, 'imagen' => 'img/comidas/chow-fan.webp'],
            ],
        ];

        foreach ($menu as $nombreCategoria => $platos) {
            $categoria = Categoria::firstOrCreate(['nombre' => $nombreCategoria]);

            foreach ($platos as $plato) {
                Plato::firstOrCreate(
                    ['categoria_id' => $categoria->id, 'nombre' => $plato['nombre']],
                    array_merge($plato, ['stock' => 0, 'activo' => true])
                );
            }
        }
    }
}
