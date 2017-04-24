<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
| Note: The name space is "App\Http\Controller\Api"
|
*/

Route::group(["prefix" => "v1"], function () {
    Route::group(["middleware" => 'auth:api'], function () {
        // Auth Routes
        Route::get('/logout', 'Auth\LoginController@logout');

        // Resources
        // Posts Private Routes
        Route::resource('posts', 'PostController', ['only' => ['store', 'update', 'destroy']]);
    });

    // Public Apis
    // Users Routes
    Route::get('users/{user}/posts', 'UserController@getPosts')->name('users.posts');
    Route::resource('users', 'UserController', ['only' => ['show']]);

    // Categories Routes
    Route::resource('categories', 'CategoryController', ['only' => ['index']]);

    // Posts Routes
    Route::resource('posts', 'PostController', ['only' => ['index', 'show']]);

    // Auth Routes
    Route::post('/login', 'Auth\LoginController@login')->name('api.login');
});

// Fallback Route
Route::any('{all}', function () {
    $request = $this->getCurrentRequest();
    if ($request->wantsJson()) {
        return response()->json(["error" => $request->method() . " " . $request->fullUrl() . " not found"], 404);
    }
    abort(404);

})->where(['all' => '.*']);


