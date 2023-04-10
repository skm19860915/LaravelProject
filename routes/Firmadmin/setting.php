<?php

Route::get('setting/app', 'firmadmin\FirmSettingController@Apps')->name('setting.app');
Route::get('setting/QbookConnect', 'firmadmin\FirmSettingController@QbookConnect')->name('setting.QbookConnect');
Route::get('setting/QBautoconnect', 'firmadmin\FirmSettingController@autoQuickBookToken')->name('setting.QBautoconnect');





Route::get('setting/sms', 'firmadmin\FirmSettingController@smsindex')->name('setting.sms');
Route::get('setting/sms/getData', 'firmadmin\FirmSettingController@smsgetData')->name('setting.sms.getData');



Route::get('setting/email', 'firmadmin\FirmSettingController@emailindex')->name('setting.email');
Route::get('setting/email/getData', 'firmadmin\FirmSettingController@emailgetData')->name('setting.email.getData');



Route::get('setting/update/{id}', 'firmadmin\FirmSettingController@messageUpdate')->name('setting.update');
Route::post('setting/update_message', 'firmadmin\FirmSettingController@update_message')->name('setting.update_message');

Route::post('setting/calendar_setting', 'firmadmin\FirmSettingController@calendar_setting')->name('setting.calendar_setting');


Route::get('setting/app_setting', 'firmadmin\FirmSettingController@app_setting')->name('setting.app_setting');

Route::post('setting/update_app_setting', 'firmadmin\FirmSettingController@update_app_setting')->name('setting.update_app_setting');

Route::get('setting/theme_setting', 'firmadmin\FirmSettingController@theme_setting')->name('setting.theme_setting');

Route::post('setting/update_theme_setting', 'firmadmin\FirmSettingController@update_theme_setting')->name('setting.update_theme_setting');	

/*Route::get('setting/delete/{id}', 'firmadmin\FirmSettingController@delete')->name('setting.delete');
*/


/*Route::get('case/mycase', 'firmadmin\FirmCaseController@mycase')->name('case.mycase');
Route::get('case/allcase', 'firmadmin\FirmCaseController@allcase')->name('case.allcase');
Route::get('case/create', 'firmadmin\FirmCaseController@create')->name('case.create');
Route::get('case/getData', 'firmadmin\FirmCaseController@getData')->name('case.getData');
Route::get('case/delete/{id}', 'firmadmin\FirmCaseController@delete')->name('case.delete');
Route::post('case/create_case', 'firmadmin\FirmCaseController@create_case')->name('case.create_case');
Route::get('case/show/{id}', 'firmadmin\FirmCaseController@show')->name('case.show');*/


