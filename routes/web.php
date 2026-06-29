<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Redirigir todas las rutas web no existentes a la vista principal para que React Router las maneje ss
Route::get('{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');

