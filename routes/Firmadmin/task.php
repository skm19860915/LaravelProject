<?php
	Route::get('task', 'firmadmin\FirmTaskController@index')->name('task');
	Route::get('task/getData', 'firmadmin\FirmTaskController@getData')->name('task.getData');
	Route::get('task/getCasetask', 'firmadmin\FirmTaskController@getCasetask')->name('task.getCasetask');
	
	Route::get('task/create', 'firmadmin\FirmTaskController@create')->name('task.create');
	Route::post('task/create_task', 'firmadmin\FirmTaskController@create_task')->name('task.create_task');

	Route::get('task/delete/{id}', 'firmadmin\FirmTaskController@delete')->name('task.delete');

	Route::get('task/show/{id}', 'firmadmin\FirmTaskController@show')->name('task.show');

	// Route::get('task/timeline/{id}', 'admin\TaskController@timeline')->name('task.timeline');