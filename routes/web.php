<?php

use App\Models\User;
use App\Events\UserCreated;
use Illuminate\Support\Sleep;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    app('db')->transaction(function () {
        $user = User::factory()
            ->create();

        UserCreated::dispatch($user);

        Sleep::for(5)->seconds();
    });

    dump('done');
});
