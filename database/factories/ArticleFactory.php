<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
	$mayoristas= ['Buenos Aires', 'Rosario', 'Zona Sur', 'Cordoba', 'Gualeguay', 'Gualeguaychu', 'Victoria', 'Otra Ciudad'];
    $costo= rand(30, 5000);
    $precio= $costo+($costo*0.4);
    $precio_anterior= rand(30, 5000);
    return [
        'codigo_barras' => rand(1000000, 9999999),
        'name'          => $faker->name,
        'mayorista'     => $mayoristas[array_rand($mayoristas)],
        'cost'          => $costo,
        'price'         => $precio,
        'stock'         => rand(4, 12)
    ];
});
