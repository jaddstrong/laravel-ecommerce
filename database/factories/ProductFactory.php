<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->text($maxNbChars = 50)  ,
        'price' => $faker->biasedNumberBetween($min = 50, $max = 500, $function = 'sqrt'),
        'stack' => $faker->biasedNumberBetween($min = 100, $max = 1000, $function = 'sqrt'),
        'img' => 'default-pro.jpg',
        'drop' => false,
    ];
});
