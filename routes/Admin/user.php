<?php 
	Route::get('get_user_data', 'admin\UserController@get_user_data')->name('get_user_data');
    Route::get('get_cases_data', 'admin\UserController@get_cases_data')->name('get_cases_data');
    Route::get('users/roles', 'admin\UserController@roles')->name('users.roles');
    Route::get('users/show/{id}', 'admin\UserController@show')->name('users.show');
    Route::get('users/cases/{id}', 'admin\UserController@cases')->name('users.cases');
    Route::get('users/assigned/{id}', 'admin\UserController@assigned')->name('users.assigned');
    Route::get('users/tasks/{id}', 'admin\UserController@tasks')->name('users.tasks');
    Route::get('users/getTaskData', 'admin\UserController@getTaskData')->name('users.getTaskData');
    Route::get('users/newtasks/{id}', 'admin\UserController@newtasks')->name('users.newtasks');
    Route::get('users/viewclient/{cid}', 'admin\UserController@viewclient')->name('users.viewclient');
    Route::get('users/delete/{id}', 'admin\UserController@delete')->name('users.delete');
    Route::post('users/create_user', 'admin\UserController@create_user')->name('users.create_user');
    Route::post('users/update_user', 'admin\UserController@update_user')->name('users.update_user');

    
    Route::resource('users', 'admin\UserController', [
        'names' => [
            'index' => 'users'
        ]
    ]);