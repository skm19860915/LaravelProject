<?php
	Route::get('mysupport', 'adminsupport\MyTicketController@index')->name('mysupport');
	Route::get('mysupport/getData', 'adminsupport\MyTicketController@getData')->name('mysupport.getData');

	Route::get('mysupport/show/{id}', 'adminsupport\MyTicketController@show')->name('mysupport.show');

	Route::get('mysupport/chat/{id}', 'adminsupport\MyTicketController@chat')->name('mysupport.chat');