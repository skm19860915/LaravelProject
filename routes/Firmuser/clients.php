<?php
Route::get('firmclients', 'firmuser\FirmClientController@index')->name('firmclients');
Route::get('firmclients/getData', 'firmuser\FirmClientController@getData')->name('firmclients.getData');
Route::get('firmclient/show/{id}', 'firmuser\FirmClientController@show')->name('firmclient.show');