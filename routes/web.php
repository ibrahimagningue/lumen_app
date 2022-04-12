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


$router->post('/user/create', 'UserController@create');
$router->post('/user/update', 'UserController@update');
$router->post('/user/delete', 'UserController@delete');
$router->post('/user/search', 'UserController@search');
$router->get('/test', 'UserController@test');


$router->get('/', function () use ($router) {
    return $router->app->version();
});
