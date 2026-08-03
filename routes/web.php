<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

//garante que os jobs sejam executados em ordem, mesmo que um job falhe, os outros jobs não serão executados
    Bus::chai([
        new Job1,
        new Job2,
        new Job3,
    ]);

    //eles não serão executados em ordem, se um job falhar, os outros jobs serão executados
    Bus::batch([
        new Job1,
        new Job2,
        new Job3,
    ])->then(function () {
        // Handle successful completion
    })->catch(function (Exception $e) {
        // Handle failure
    })->finally(function () {
        // The batch has finished executing
    })->dispatch();


     return view('welcome');

});
