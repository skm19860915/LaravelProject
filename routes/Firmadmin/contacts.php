<?php
Route::get('contacts', 'firmadmin\FirmContactController@index')->name('contacts');
Route::get('contacts/getData', 'firmadmin\FirmContactController@getData')->name('contacts.getData');
Route::get('contacts/show/{id}', 'firmadmin\FirmContactController@show')->name('contacts.show');
Route::post('contacts/send_message', 'firmadmin\FirmContactController@send_message')->name('contacts.send_message');