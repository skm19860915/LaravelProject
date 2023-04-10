<?php
	Route::get('usertips', 'adminuser\UserTipsController@index')->name('usertips');
	
	Route::get('usertips/getData', 'adminuser\UserTipsController@getData')->name('usertips.getData');
	
	Route::get('usertips/tips_show/{id}', 'adminuser\UserTipsController@tips_show')->name('usertips.tips_show');
