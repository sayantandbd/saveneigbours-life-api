<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


// User
$router->group(['prefix' => 'user'], function () use ($router) {
    // Matches "/api/register
   $router->post('register', 'UserController@register');
   $router->post('login', 'UserController@login');

   $router->get('profile', ['middleware' => 'auth', 'uses' => 'UserController@profile']);
   $router->get('profile/{id}', ['middleware' => 'auth', 'uses' => 'UserController@profile_details']);
   $router->post('profile-update', ['middleware' => 'auth', 'uses' => 'UserController@profile_update']);
});

$router->group(['prefix' => 'requests'], function () use ($router) {
    $router->post('create', ['middleware' => 'auth', 'uses' => 'RequestController@create']);
    $router->get('list', ['middleware' => 'auth', 'uses' => 'RequestController@list']);
});
