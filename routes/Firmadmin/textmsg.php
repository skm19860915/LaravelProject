<?php
	Route::get('client/text_message/{id}', 'firmadmin\FirmTextMsgController@text_message')->name('client.text_message');
	Route::post('client/send_text_msg', 'firmadmin\FirmTextMsgController@send_text_msg')->name('client.send_text_msg');
	Route::get('client/text_message/getData/{id}', 'firmadmin\FirmTextMsgController@getData')->name('client.text_message.getData');

