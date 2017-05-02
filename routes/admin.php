<?php

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
| 
| Routes Prefix: admin/
| namespace: App\Http\Controller\Admin
*/


// Admin Interface Routes
Route::group(['middleware' => 'admin'], function()
{
    // Backpack\CRUD: Define the resources for the entities you want to CRUD.
    Route::put('users/{id}/restore', 'UserCrudController@restore')->name('crud.users.restore');
    CRUD::resource('users', 'UserCrudController');
    CRUD::resource('posts', 'PostCrudController');
    CRUD::resource('post_property', 'PostPropertyCrudController');
    CRUD::resource('categories', 'CategoryCrudController');
    CRUD::resource('category_property', 'CategoryPropertyCrudController');
    CRUD::resource('requests', 'RequestModelCrudController');
    CRUD::resource('request_property', 'RequestPropertyCrudController');

});

Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/login', 'Auth\AdminLoginController@login');
Route::get('/logout', 'Auth\AdminLoginController@logout');
