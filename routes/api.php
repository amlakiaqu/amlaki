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
use Illuminate\Http\Request;

Route::group(["prefix" => "v1"], function () {
    Route::group(["middleware" => 'auth:api'], function () {
        // Auth Routes
        Route::get('/logout', 'Auth\LoginController@logout');

        // Resources
        Route::get('users/{user}/requests', 'UserController@getRequests')->name('users.requests');
        Route::put('requests/{id}', 'RequestController@update')->name('requests.update');
        Route::get('requests/{id}', 'RequestController@show')->name('requests.show');

        Route::resource('requests', 'RequestController', ['only' => ['store', 'destroy']]);

        // Posts Private Routes
        Route::resource('posts', 'PostController', ['only' => ['store', 'update', 'destroy']]);
    });

//    Route::post('/files', function(Request $request){
//        if( $request->hasFile('image')){
//            $imageFile = $request->file('image');
//            $path = \Storage::putFile('images', $imageFile, 'public');
//            return response()->json(['message' => 'file saved successfully', 'path' => $path]);
//        }else{
//            return response()->json(['message' => 'file \'image\' is required'], 400);
//        }
//    });

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


