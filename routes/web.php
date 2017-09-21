<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function () {


    Route::get('/', 'ServerController@dashboard');



    /*************************
    Get models
    /************************/
    Route::get('/server/categories', 'ServerController@getServerCategories') ;
    Route::get('/server/{serverId}', 'ServerController@getServer');
    Route::get('/servers/{filter?}', 'ServerController@getServers');

    Route::get('/applications/{server}', 'ServerController@getApplications');
    Route::get('/notifications/{app}/', 'ServerController@getNotifications'); 
    Route::get('/thresholds/{server}/', 'ServerController@getThresholds'); 
    Route::get('/metrics/{server}/{quantity?}', 'ServerController@getMetrics'); 
    Route::get('/deployments/{app}/{initial}/{quantity}', 'ServerController@getDeployments'); 
    Route::get('/applications/{branch}/{repo}', 'ServerController@getApplicationsForDeployment');

    /*************************
    Save models
    /************************/
    Route::post('/server', 'ServerController@saveServer');
    Route::post('/application', 'ServerController@saveApplication');
    Route::post('/applicationNotification', 'ServerController@saveApplicationNotification');
    Route::post('/threshold', 'ServerController@saveThreshold');
    Route::post('/metric', 'ServerController@saveMetric');
    Route::post('/deployment', 'ServerController@saveDeployment');
    Route::post('/launchDeployment', 'ServerController@launchDeployment');
    Route::post('/server/category', 'ServerController@saveServerCategory');

    //Delete models
    Route::delete('/applicationNotification', 'ServerController@deleteApplicationNotification');
    Route::delete('/server/category', 'ServerController@deleteServerCategory');


});






// Github webhook for autodeployment
Route::post('/webhook', 'ServerController@webhook');
// Check servers thresholds and send notifications
Route::get('/cron', 'ServerController@cron');
//Get metrics from server
Route::get('/hookServer', 'ServerController@hookServer');
//Delete old metrics 
Route::get('/cronDeleteMetrics', 'ServerController@cronDeleteMetrics');



Auth::routes();


//Route::get('/home', 'HomeController@index');


// Route::get('/tasks', function () {
//     $exitCode = Artisan::call('list:server', [
//         'route' => '/var/www/html',
//         'branch' => 'australia/develop',
//         'server' => 'AusApiServer'
//     ]);
// });


// Route::get('/ausdb', function (){
//     $exitCode = Artisan::call('list:server', [ 
//         'route' => '/var/solr/data',
//         'branch' => 'australia',
//         'server' => 'AusDServer'
//     ]);
// });


// use App\Jobs\ProcessGithubDeploy;
// Route::get('/samplejob', function(){
//     $deployJob = (new ProcessGithubDeploy("master", "Andres Quintero", "deploytest"))->onQueue('deployments');
//     dispatch($deployJob);
//     return "hola2";
// });


