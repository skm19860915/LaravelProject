<?php
Route::get('clientchat', 'firmclient\FirmChatController@index')->name('clientchat.index');
Route::get('clientchat/{ids}', 'firmclient\FirmChatController@chat')->name('clientchat.chat');