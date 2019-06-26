<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Game;
use Faker\Generator as Faker;

$factory->define(Game::class, function (Faker $faker) {
    return [
        'target_score' => $faker->numberBetween(301, 501)
    ];
});

$factory->state(Game::class, 'with-winner', function (Faker $faker) {
    return [
        'winner_id' => factory(\App\User::class)->create()->id
    ];
});
