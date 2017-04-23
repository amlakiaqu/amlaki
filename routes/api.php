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

Route::group(["prefix" => "v1" ], function(){
	Route::group(["middleware" => 'auth:api'], function(){
		Route::get('/logout', 'Auth\LoginController@logout');

		// Resources
        Route::get('users/{user}/posts', 'UserController@getPosts');
        Route::resource('users', 'UserController', ['only' => ['show']]);
        Route::resource('categories', 'CategoryController', ['only' => ['index']]);
        Route::resource('posts', 'PostController', ['except' => ['index', 'show']]);

	});

	// Public Apis
	Route::get('posts', 'PostController@index')->name('posts.index');
	Route::get('posts/{post}', 'PostController@show')->name('posts.show');
	Route::post('/login', 'Auth\LoginController@login')->name('api.login');
    Route::get('/broadcast', function() {
        event(new App\Events\PostCreatedEvent(["message" => 'Hi there Pusher!']));
        return response()->json("message sent successfully");
    });
});

Route::any('{all}', function () {
	$request = $this->getCurrentRequest();
	if($request->wantsJson()){
		return response()->json(["error" => $request->method()." ".$request->fullUrl()." not found"], 404);
	}
    abort(404);

})->where(['all' => '.*']);


