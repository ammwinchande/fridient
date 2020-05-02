<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Box;
use Faker\Generator as Faker;

$factory->define(Box::class, function (Faker $faker) {
    return [
        'delivery_date' => $faker->date(),
        'recipe_ids' => $faker->shuffle([1, 2, 3, 4])
    ];
});
