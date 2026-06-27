<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    $result = Http::get('https://api.github.com/repos/laravel/laravel/pulls?state=all');

    dd($result->json());
});
