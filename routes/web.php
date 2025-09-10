<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__ . '/auth.php';


Route::get('/csrf-token', function (Request $request) {
    $response = Route::dispatch(Request::create('/sanctum/csrf-cookie', 'GET'));

    $cookies = $response->headers->getCookies();
    $xsrfToken = null;

    foreach ($cookies as $cookie) {
        if ($cookie->getName() === 'XSRF-TOKEN') {
            $xsrfToken = $cookie->getValue();
            break;
        }
    }

    return response()->json([
        'token' => $xsrfToken,
    ])->withCookie(...$cookies);
});
