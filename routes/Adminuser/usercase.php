<?php
	Route::get('readycase', 'adminuser\UserCaseController@readycase')->name('readycase');
	
	Route::get('readycase/getDataReady', 'adminuser\UserCaseController@getDataReady')->name('readycase.getDataReady');



	Route::get('pendingcase', 'adminuser\UserCaseController@pendingcase')->name('pendingcase');
	
	Route::get('pendingcase/getDataPending', 'adminuser\UserCaseController@getDataPending')->name('pendingcase.getDataPending');


	Route::get('complitcase', 'adminuser\UserCaseController@complitcase')->name('complitcase');

	Route::get('complitcase/getDataComplit', 'adminuser\UserCaseController@getDataReady')->name('complitcase.getDataComplit');

	Route::get('all_case', 'adminuser\UserCaseController@all_case')->name('all_case');
	
	Route::get('all_case/getDataAll', 'adminuser\UserCaseController@getDataAll')->name('all_case.getDataAll');