<?php
Route::get('forms/{id}', 'firmadmin\AddFormController@index')->name('forms');
Route::get('form/addform/{id}', 'firmadmin\AddFormController@addform')->name('forms.addform');
Route::post('forms/create_form', 'firmadmin\AddFormController@create_form')->name('forms.create_form');
Route::get('forms/client_Cases/{id}', 'firmadmin\AddFormController@client_Cases')->name('forms.client_Cases');
Route::post('forms/getForms', 'firmadmin\AddFormController@getForms')->name('forms.getForms');
Route::post('forms/information_update', 'firmadmin\AddFormController@information_update')->name('forms.information_update');
Route::post('forms/update_form_status', 'firmadmin\AddFormController@update_form_status')->name('forms.update_form_status');