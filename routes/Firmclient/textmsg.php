<?php
	Route::get('textmessage/{id}', 'firmclient\TextMsgController@textmessage')->name('textmessage');
	Route::post('sendtextmsg', 'firmclient\TextMsgController@sendtextmsg')->name('sendtextmsg');
	Route::get('textmessages/getData', 'firmclient\TextMsgController@getData')->name('textmessages.getData');

	Route::get('mymessages', 'firmclient\TextMsgController@mymessages')->name('mymessages');


