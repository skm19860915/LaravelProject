<?php
Route::get('userchat', 'firmuser\FirmChatController@index')->name('userchat.index');
Route::get('userchat/{ids}', 'firmuser\FirmChatController@chat')->name('userchat.chat');