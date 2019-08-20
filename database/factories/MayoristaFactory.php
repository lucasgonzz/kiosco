<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;
$cont=0;

$factory->define(Model::class, function (Faker $faker) {
	$mayoristas= ['Buenos Aires', 'Rosario', 'Zona Sur', 'Cordoba', 'Gualeguay', 'Gualeguaychu', 'Victoria', 'Otra Ciudad', 'Santa Fe', 'Zona Norte'];
	$cont++;
    return [
        'name' => $mayoristas[$cont]
    ];
});
