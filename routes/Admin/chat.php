<?php
Route::get('chat', 'admin\ChatController@index')->name('chat.index');
Route::get('chat/{ids}', 'admin\ChatController@chat')->name('chat.chat');