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

    // Matches "/api/profile
    $router->get('profile', 'UserController@profile');

    // Matches "/api/users/1
    //get one user by id
    $router->get('users/{id}', 'UserController@view');

    // Matches "/api/users
    $router->get('users', 'UserController@allUsers');

    // Matches "/api/tasks
    $router->get('tasks', 'TaskController@allTasks');

    // Matches "/api/tasks/create
    $router->post('tasks/create', 'TaskController@create');

    // Matches "/api/tasks/1
    $router->put('tasks/{id}', 'TaskController@update');

    // Matches "/api/tasks/1
    $router->get('tasks/{id}', 'TaskController@view');

    // Matches "/api/tasks/delete/1
    $router->delete('tasks/delete/{id}', 'TaskController@delete');
});
