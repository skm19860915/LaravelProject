<?php
Route::get('firmcontacts', 'firmuser\FirmContactController@index')->name('firmcontacts');
Route::get('firmcontacts/getData', 'firmuser\FirmContactController@getData')->name('firmcontacts.getData');
Route::get('firmcontacts/show/{id}', 'firmuser\FirmContactController@show')->name('firmcontacts.show');
Route::post('firmcontacts/send_message', 'firmuser\FirmContactController@send_message')->name('firmcontacts.send_message');