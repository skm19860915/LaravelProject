<?php
Route::get('userchat', 'adminuser\ChatController@index')->name('userchat.index');
Route::get('userchat/{ids}', 'adminuser\ChatController@chat')->name('userchat.chat');