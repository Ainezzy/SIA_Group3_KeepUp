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

$router->get('/dashboard', function () {
    return file_get_contents(__DIR__ . '/../public/index.html');
});

$router->get('/login', function () {
    return file_get_contents(__DIR__ . '/../public/login.html');
});


$router->get('/register', function () {
    return file_get_contents(__DIR__ . '/../public/register.html');
});


$router->group(['middleware'=>'client.credentials'], function() use ($router){

    $router->post('/login', 'AuthController@login');
    $router->post('/register', 'AuthController@register');

    $router->get('/news', 'NewsController@getNews');
    $router->get('/quote', 'QuoteController@getRandomQuote');
    $router->get('/weather', 'WeatherController@getWeather');

    // Updated route for getting all data with query parameters
    $router->get('/all-data', 'GatewayController@getAllData');


    //SIGN IN ROUTES
    $router->get('/signinusers', 'SignInController@index');
    $router->post('/signinusers', 'SignInController@add');
    $router->get('/signinusers/{id}', 'SignInController@show');
    $router->put('/signinusers/{id}', 'SignInController@update'); // update user
    $router->patch('/signinusers/{id}', 'SignInController@update'); // update user
    $router->delete('/signinusers/{id}', 'SignInController@delete'); // delete user

    //SIGN UP ROUTES
    $router->get('/signupusers', 'SignUpController@index');
    $router->post('/signupusers', 'SignUpController@add');
    $router->get('/signupusers/{id}', 'SignUpController@show');
    $router->put('/signupusers/{id}', 'SignUpController@update'); // update user
    $router->patch('/signupusers/{id}', 'SignUpController@update'); // update user
    $router->delete('/signupusers/{id}', 'SignUpController@delete'); // delete user
});