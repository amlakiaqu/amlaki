<?php

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
| 
| Routes Prefix: admin/
| namespace: App\Http\Controller\Admin
*/


Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/login', 'Auth\AdminLoginController@login');
Route::get('/logout', 'Auth\AdminLoginController@logout');
