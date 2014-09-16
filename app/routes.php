<?php
Route::get('/',                           ['uses' => 'HomeController@index', 'as' => 'home']);

// Packages
Route::get('/packagess',                  ['uses' => 'PackagesController@index', 'as' => 'packages']);
Route::get('/package/{author}/{package}', ['uses' => 'PackagesController@view', 'as' => 'package']);
Route::get('/update-package/{id}',        ['uses' => 'PackagesController@update', 'as' => 'update-package']);

// Tags
Route::get('/tags',                       ['uses' => 'TagsController@index', 'as' => 'tags']);
Route::get('/tag/{id}',                   ['uses' => 'TagsController@view', 'as' => 'tag']);
Route::get('/tag/{id}/{slug}',            ['uses' => 'TagsController@view', 'as' => 'tag']);

// Authors
Route::get('/authors',                    ['uses' => 'AuthorsController@index', 'as' => 'authors']);
Route::get('/author/{id}',                ['uses' => 'AuthorsController@view', 'as' => 'author']);
Route::get('/author/{id}/{slug}',         ['uses' => 'AuthorsController@view', 'as' => 'author']);

Route::get('/cron-refresh-all',           ['uses' => 'CronController@refreshAll']);
Route::get('/cron-refresh-package',       ['uses' => 'CronController@refreshPackage']);
