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

// $app->post('registration', 'PlayersController@postRegistration');
$app->post('registration',  ['middleware' => 'jwt.auth', 'uses' => 'PlayersController@postRegistration']);

//helper
$app->get('get-locations', 'CitiesController@getLocation');
$app->get('get-districts/{id}', 'CitiesController@getDistrictsByCity');
$app->get('get-properties/{name}', 'PropertiesController@getPropertiesByName');
$app->get('get-all-properties', 'PropertiesController@getAllProperties');

//finding-player
$app->get('get-finding-players/{district}/{position}/{level}', 
    ['middleware' => 'jwt.auth', 'uses' => 'TeamFindingPlayersController@getFindingPlayers']);
$app->post('add-finding-player', ['middleware' => 'jwt.auth', 'uses' =>'TeamFindingPlayersController@addFindingPlayer']);
$app->get('get-finding-player/{id}', ['middleware' => 'jwt.auth', 'uses' => 'TeamFindingPlayersController@getFindingPlayerbyId']);

//finding-match
$app->get('get-finding-matchs/{district}/{level}', 
    ['middleware' => 'jwt.auth', 'uses' => 'TeamFindingMatchsController@getFindingMatchs']);
$app->post('add-finding-match', ['middleware' => 'jwt.auth', 'uses' =>'TeamFindingMatchsController@addFindingMatch']);
$app->get('get-finding-match/{id}', ['middleware' => 'jwt.auth', 'uses' => 'TeamFindingMatchsController@getFindingMatchbyId']);

//finding-Team
$app->get('get-finding-teams/{district}/{position}/{level}', 
    ['middleware' => 'jwt.auth', 'uses' => 'PlayerFindingTeamsController@getFindingTeams']);
$app->post('add-finding-team', ['middleware' => 'jwt.auth', 'uses' => 'PlayerFindingTeamsController@addFindingTeam']);
$app->get('get-finding-team/{id}', ['middleware' => 'jwt.auth', 'uses' => 'PlayerFindingTeamsController@getFindingTeambyId']);

//notification
$app->get('get-notifications', ['middleware' => 'jwt.auth', 'uses' => 'NotificationsController@getNotifications']);

//stadium
$app->get('get-stadium-by-district/{districtId}', ['middleware' => 'jwt.auth', 'uses' => 'StadiumsController@getStadiumsByDistrict']);

// notification setting
$app->get('get-notification-setting/{type}', ['middleware' => 'jwt.auth', 'uses' => 'NotificationSettingsController@getNotificationSetting']);
$app->post('save-notification-setting', ['middleware' => 'jwt.auth', 'uses' => 'NotificationSettingsController@saveNotificationSetting']);


// TMP
$app->get('/login/{email}/{name}', 'AuthController@login');
$app->get('/registration/{email}/{registrationId}', 'PlayersController@registration');
