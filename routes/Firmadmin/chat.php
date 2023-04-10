<?php
Route::get('chat', 'firmadmin\FirmChatController@index')->name('chat.index');
Route::get('chat/{ids}', 'firmadmin\FirmChatController@chat')->name('chat.chat');