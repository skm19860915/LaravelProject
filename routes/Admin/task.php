<?php
	Route::get('task', 'admin\TaskController@index')->name('task');
	Route::get('task/getData', 'admin\TaskController@getData')->name('task.getData');
	
	Route::get('task/edit/{id}', 'admin\TaskController@edit')->name('task.edit');
	Route::post('task/update_task', 'admin\TaskController@update')->name('task.update_task');

	Route::get('task/delete/{id}', 'admin\TaskController@delete')->name('task.delete');

	Route::get('task/show/{id}', 'admin\TaskController@show')->name('task.show');

	Route::get('task/timeline/{id}', 'admin\TaskController@timeline')->name('task.timeline');

	Route::get('task/create', 'admin\TaskController@create')->name('task.create');

	Route::post('task/create_task', 'admin\TaskController@create_task')->name('task.create_task');

	Route::get('task/edit1/{id}', 'admin\TaskController@edit1')->name('task.edit1');
	Route::post('task/update_task1', 'admin\TaskController@update_task1')->name('task.update_task1');
	Route::get('task/delete_firm_account/{id}', 'admin\TaskController@delete_firm_account')->name('delete_firm_account');