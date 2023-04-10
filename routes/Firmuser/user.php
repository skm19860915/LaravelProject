<?php
	Route::get('firmusers/getData', 'firmuser\FirmUserController@getData')->name('firmusers.getData');
    Route::resource('firmusers', 'firmuser\FirmUserController', [
        'names' => [
            'index' => 'firmusers'
        ]
    ]);