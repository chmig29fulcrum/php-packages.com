<?php
$cronPassword = Config::get('app.cron_password');

Route::get('/', function(){ return Redirect::route('home'); });
Route::get('/home',                        ['uses' => 'HomeController@index', 'as' => 'home']);

// Packages
Route::get('/packagess',                   ['uses' => 'PackagesController@index', 'as' => 'packages']);
Route::post('/packagess',                  ['uses' => 'PackagesController@index']);
Route::get('/package/{author}',            ['uses' => 'PackagesController@author', 'as' => 'packages-author']);
Route::get('/package/{author}/{package}',  ['uses' => 'PackagesController@view', 'as' => 'package']);
Route::get('/update-package/{a}/{n}',      ['uses' => 'PackagesController@refreshPackage', 'as' => 'update-package']);

Route::get('/search-pakage-types',         ['uses' => 'PackagesController@ajaxSearchPackages', 'as' => 'search-pakage-types']); // ajax

// Tags
Route::get('/tags',                        ['uses' => 'TagsController@index', 'as' => 'tags']);
Route::get('/tag/{id}',                    ['uses' => 'TagsController@view', 'as' => 'tag']);
Route::get('/tag/{id}/{slug}',             ['uses' => 'TagsController@view', 'as' => 'tag']);

Route::get('/search-tags',                 ['uses' => 'TagsController@ajaxSearchTags', 'as' => 'search-tags']); // ajax
Route::get('/search-tags-init/',           ['uses' => 'TagsController@ajaxSearchTagsInit', 'as' => 'search-tags-init']); // ajax

// Authors
Route::get('/authors',                     ['uses' => 'AuthorsController@index', 'as' => 'authors']);
Route::get('/author/{id}',                 ['uses' => 'AuthorsController@view', 'as' => 'author']);
Route::get('/author/{id}/{slug}',          ['uses' => 'AuthorsController@view', 'as' => 'author']);

// Crons
Route::get('/cron-one-day-'.$cronPassword, ['uses' => 'PackagesController@checkForNewPackages']);
Route::get('/cron-one-min-'.$cronPassword, ['uses' => 'PackagesController@updateOldestPackage']);
