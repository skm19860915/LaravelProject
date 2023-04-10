<?php
	Route::get('helpfull_tips', 'admin\TipsController@index')->name('helpfull_tips');
	
	Route::get('helpfull_tips/create', 'admin\TipsController@create')->name('helpfull_tips.create');
	Route::post('helpfull_tips/create_tips', 'admin\TipsController@create_tips')->name('helpfull_tips.create_tips');

	Route::get('helpfull_tips/getData', 'admin\TipsController@getData')->name('helpfull_tips.getData');
	
	Route::get('helpfull_tips/tips_show/{id}', 'admin\TipsController@tips_show')->name('firm.tips_show');

	
	Route::get('helpfull_tips/tips_edit/{id}', 'admin\TipsController@tips_edit')->name('helpfull_tips.tips_edit');
	Route::post('helpfull_tips/update_tips', 'admin\TipsController@update_tips')->name('helpfull_tips.update_tips');
	

	Route::get('helpfull_tips/delete/{id}', 'admin\TipsController@delete')->name('helpfull_tips.delete');
