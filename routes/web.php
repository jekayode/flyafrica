<?php


Route::get('/', function () {
    return view('welcome');
});

Route::get('/flightsearch', 'FlightController@index');


