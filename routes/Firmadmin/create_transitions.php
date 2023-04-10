<?php	
Route::get('transition/{id}', 'firmadmin\FirmTrasitionController@transition')->name('transition');
Route::get('transition/create/{id}', 'firmadmin\FirmTrasitionController@create')->name('create');
Route::post('transition/create_transition', 'firmadmin\FirmTrasitionController@create_transition')->name('create_transition');
Route::get('transition/getDataTransition/{id}', 'firmadmin\FirmTrasitionController@getDataTransition')->name('getDataTransition');