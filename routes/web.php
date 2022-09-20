<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'api'], function () use ($router) {
    // Matches "/api/register
    $router->post('register', 'AuthController@register');

    // Matches "/api/login
    $router->post('login', 'AuthController@login');

    // Matches "/api/users
    $router->group(['middleware' => 'auth', 'prefix' => 'users'], function () use ($router) {
        $router->get('/', 'UserController@list');
        $router->get('show/{id}', 'UserController@show');
        $router->get('profile', 'UserController@profile');
    });

    // Matches "/api/tasks
    $router->group(['middleware' => 'auth', 'prefix' => 'tasks'], function () use ($router) {
        $router->get('/', 'TaskController@list');
        $router->get('/{id}', 'TaskController@show');
        $router->post('create', 'TaskController@create');
        $router->put('/{id}', 'TaskController@update');
        $router->delete('delete/{id}', 'TaskController@delete');
    });
});
