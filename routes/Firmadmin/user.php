<?php
	Route::get('users/getData', 'firmadmin\FirmUserController@getData')->name('users.getData');
    Route::get('users/delete/{id}', 'firmadmin\FirmUserController@delete')->name('users.delete');
    Route::get('users/addnewuser', 'firmadmin\FirmUserController@addnewuser')->name('users.addnewuser');
    Route::post('users/create_user', 'firmadmin\FirmUserController@create_user')->name('users.create_user');
    Route::post('users/createuser', 'firmadmin\FirmUserController@createuser')->name('users.createuser');
    Route::post('users/create_user1', 'firmadmin\FirmUserController@create_user1')->name('users.create_user1');
    Route::get('users/edit/{id}', 'firmadmin\FirmUserController@edit')->name('users.edit');
    Route::post('users/update', 'firmadmin\FirmUserController@update')->name('users.update');
    Route::resource('users', 'firmadmin\FirmUserController', [
        'names' => [
            'index' => 'users'
        ]
    ]);
    Route::get('users/deletenew/{id}', 'firmadmin\FirmUserController@deletenew')->name('users.deletenew');

    Route::post('users/update_new', 'firmadmin\FirmUserController@update_new')->name('users.update_new');

    