<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

abstract class Controller
{
    public function __invoke()
    {
        //Garantir que os endpoints estejam corretos e os parametros estejam corretos
        //Garantir que a API (codigo) esteja funcionando corretamente

        $result = Http::get('https://api.github.com');

        dd($result->json());
    }
}
