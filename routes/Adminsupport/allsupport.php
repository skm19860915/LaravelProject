<?php
	Route::get('allsupport', 'adminsupport\AllTicketController@index')->name('allsupport');
	Route::get('allsupport/getData', 'adminsupport\AllTicketController@getData')->name('allsupport.getData');

	Route::get('allsupport/accept/{id}', 'adminsupport\AllTicketController@accept')->name('allsupport.accept');

	Route::get('allsupport/show/{id}', 'adminsupport\AllTicketController@show')->name('allsupport.show');