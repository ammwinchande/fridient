<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Ingredient;
use Faker\Generator as Faker;

$factory->define(Ingredient::class, function (Faker $faker) {
    return [
        'supplier_id' => factory(App\Supplier::class),
        'name' => $faker->safeColorName,
        'measure' => $faker->hexColor
    ];
});
