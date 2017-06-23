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

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->post('/auth/login', 'AuthController@loginPost');

$app->get('foo/{id}', function ($id) {
    info('this foo is callled');
    return json_encode($id);
});

$app->get('firebase', 'FirebaseController@fireNotification');

$app->post('registration', 'PlayerController@postRegistration');

// TMP
$app->get('/login/{email}/{name}', 'AuthController@login');
$app->get('/registration/{email}/{registrationId}', 'PlayerController@registration');
