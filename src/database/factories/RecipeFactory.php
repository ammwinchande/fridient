<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Recipe;
use Faker\Generator as Faker;

$factory->define(Recipe::class, function (Faker $faker) {
    $data = [
        json_encode(["ingredient_id" => $faker->randomDigit, "amount" => $faker->randomDigit]),
        json_encode(["ingredient_id" => $faker->randomDigit, "amount" => $faker->randomDigit]),
        json_encode(["ingredient_id" => $faker->randomDigit, "amount" => $faker->randomDigit])
    ];

    return [
        'name' => $faker->safeColorName,
        'description' => $faker->sentence,
        'ingredients' => $data
    ];
});
