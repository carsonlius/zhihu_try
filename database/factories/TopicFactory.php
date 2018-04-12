<?php

use Faker\Generator as Faker;

$factory->define(App\Topic::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'brief' => $faker->paragraph,
        'questions_count' => mt_rand(100,999),
        'essences_count' => mt_rand(100, 999),
        'followers_count' => mt_rand(100, 999),
        'image' => $faker->imageUrl()
    ];
});
