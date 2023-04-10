<?php
	Route::get('usertask', 'firmuser\FirmUserTaskController@index')->name('usertask');
	Route::get('usertask/getData', 'firmuser\FirmUserTaskController@getData')->name('usertask.getData');
	Route::get('usertask/show/{id}', 'firmuser\FirmUserTaskController@show')->name('usertask.show');