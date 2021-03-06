<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Supplier;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Supplier::class, function (Faker $faker) {
    return [
        'code' => strtolower(Str::random(7)),
        'name' => $faker->company
    ];
});
