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

$app->post('/auth/login', 'AuthController@login');

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
['middleware'                                      => 'jwt.auth', 'uses' => 'TeamFindingPlayersController@getFindingPlayers']);
$app->post('add-finding-player', ['middleware'     => 'jwt.auth', 'uses' =>'TeamFindingPlayersController@addFindingPlayer']);
$app->get('get-finding-player/{id}', ['middleware' => 'jwt.auth', 'uses' => 'TeamFindingPlayersController@getFindingPlayerbyId']);

//finding-match
$app->get('get-finding-matchs/{district}/{level}', 
['middleware'                                     => 'jwt.auth', 'uses' => 'TeamFindingMatchsController@getFindingMatchs']);
$app->post('add-finding-match', ['middleware'     => 'jwt.auth', 'uses' =>'TeamFindingMatchsController@addFindingMatch']);
$app->get('get-finding-match/{id}', ['middleware' => 'jwt.auth', 'uses' => 'TeamFindingMatchsController@getFindingMatchbyId']);

//finding-Team
$app->get('get-finding-teams/{district}/{position}/{level}', 
['middleware'                                    => 'jwt.auth', 'uses' => 'PlayerFindingTeamsController@getFindingTeams']);
$app->post('add-finding-team', ['middleware'     => 'jwt.auth', 'uses' => 'PlayerFindingTeamsController@addFindingTeam']);
$app->get('get-finding-team/{id}', ['middleware' => 'jwt.auth', 'uses' => 'PlayerFindingTeamsController@getFindingTeambyId']);

//notification
$app->get('get-notifications', ['middleware'            => 'jwt.auth', 'uses' => 'NotificationsController@getNotifications']);
$app->get('count-unread-notifications', ['middleware'   => 'jwt.auth', 'uses' => 'NotificationsController@countUnreadNotifications']);
$app->post('update-notification-is-read', ['middleware' => 'jwt.auth', 'uses' => 'NotificationsController@updateNotificationIsRead']);

//stadium
$app->get('get-stadium-by-district/{districtId}', ['middleware' => 'jwt.auth', 'uses' => 'StadiumsController@getStadiumsByDistrict']);

// notification setting
$app->get('get-notification-setting/{type}', ['middleware' => 'jwt.auth', 'uses' => 'NotificationSettingsController@getNotificationSetting']);
$app->post('save-notification-setting', ['middleware'      => 'jwt.auth', 'uses' => 'NotificationSettingsController@saveNotificationSetting']);

// player
$app->get('get-player', ['middleware'     => 'jwt.auth', 'uses' => 'PlayersController@getPlayer']);
$app->get('get-player/{id}', ['middleware'     => 'jwt.auth', 'uses' => 'PlayersController@getPlayerById']);
$app->post('update-player', ['middleware' => 'jwt.auth', 'uses' => 'PlayersController@updatePlayer']);

//team
$app->post('create-team', ['middleware'     => 'jwt.auth', 'uses' => 'TeamsController@create']);
$app->post('update-team', ['middleware'     => 'jwt.auth', 'uses' => 'TeamsController@update']);
$app->get('get-team', ['middleware'         => 'jwt.auth', 'uses' => 'TeamsController@getTeam']);
$app->get('get-team-players', ['middleware' => 'jwt.auth', 'uses' => 'TeamsController@getTeamPlayers']);
$app->get('get-team/{id}', ['middleware'    => 'jwt.auth', 'uses' => 'TeamsController@getTeamById']);
$app->post('remove-member', ['middleware'     => 'jwt.auth', 'uses' => 'TeamsController@removeMember']);

//join
$app->post('join-team', ['middleware'                => 'jwt.auth', 'uses' => 'JoinsController@addJoinTeam']);
$app->get('get-joining-team-requests', ['middleware' => 'jwt.auth', 'uses' => 'JoinsController@getJoiningTeamRequests']);
$app->post('update-join-team', ['middleware'         => 'jwt.auth', 'uses' => 'JoinsController@updateJoinTeam']);
$app->post('invite-member', ['middleware'            => 'jwt.auth', 'uses' => 'JoinsController@addInviteMember']);
$app->get('get-invite-request', ['middleware'       => 'jwt.auth', 'uses' => 'JoinsController@getInviteRequest']);
$app->post('update-invite-member', ['middleware'         => 'jwt.auth', 'uses' => 'JoinsController@updateInviteMember']);


//match 
$app->post('create-match', ['middleware'       => 'jwt.auth', 'uses' => 'MatchesController@create']);
$app->get('get-matches', ['middleware'         => 'jwt.auth', 'uses' => 'MatchesController@getMatches']);
$app->get('get-match/{id}', ['middleware'      => 'jwt.auth', 'uses' => 'MatchesController@getMatchById']);
$app->post('confirm-join-match', ['middleware' => 'jwt.auth', 'uses' => 'MatchesController@confirmJoinMatch']);
