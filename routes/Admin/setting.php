<?php

Route::get('setting/appsetting', 'admin\SettingController@appsetting')->name('setting.appsetting');

Route::get('setting/app_setting', 'admin\SettingController@app_setting')->name('setting.app_setting');

Route::post('setting/app_setting_update', 'admin\SettingController@app_setting_update')->name('setting.app_setting_update');

Route::get('setting/QbookConnect', 'admin\SettingController@QbookConnect')->name('setting.QbookConnect');
Route::get('setting/QBautoconnect', 'admin\SettingController@autoQuickBookToken')->name('setting.QBautoconnect');

Route::get('setting/email', 'admin\SettingController@emailindex')->name('setting.email');
Route::get('setting/email/getData', 'admin\SettingController@emailgetData')->name('setting.email.getData');


Route::get('setting/update/{id}', 'admin\SettingController@messageUpdate')->name('setting.update');

Route::get('setting/undo_message/{id}', 'admin\SettingController@undo_message')->name('setting.undo_message');

Route::post('setting/update_message', 'admin\SettingController@update_message')->name('setting.update_message');

Route::get('setting/casetypes', 'admin\SettingController@casetypes')->name('setting.casetypes');

Route::post('setting/update_case_cost', 'admin\SettingController@update_case_cost')->name('setting.update_case_cost');