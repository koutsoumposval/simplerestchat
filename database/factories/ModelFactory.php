<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\Message;
use App\User;
use Illuminate\Support\Facades\Hash;

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => Hash::make($faker->password(6)),
    ];
});

$factory->define(Message::class, function (Faker\Generator $faker) {
    return [
        'message' => $faker->text(200),
    ];
});
