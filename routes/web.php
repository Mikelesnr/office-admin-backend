<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__ . '/auth.php';


Route::get('/csrf-token', function () {
    // Trigger Sanctum to set the CSRF cookie
    \Illuminate\Support\Facades\Route::dispatch(
        \Illuminate\Http\Request::create('/sanctum/csrf-cookie', 'GET')
    );

    // Read the token from the cookie
    $token = Cookie::get('XSRF-TOKEN');

    return response()->json([
        'token' => $token,
    ]);
});
