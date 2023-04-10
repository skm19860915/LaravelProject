<?php

Route::get('profile', 'firmadmin\FirmProfileController@index')->name('profile');

Route::post('update_profile', 'firmadmin\FirmProfileController@update_profile')->name('update_profile');


Route::get('FirmAccountSetUp', 'firmadmin\FirmDashboardController@FirmAccountSetUp')->name('firm.FirmAccountSetUp');

Route::post('FirmAccountUpdate', 'firmadmin\FirmDashboardController@FirmAccountUpdate')->name('firm.FirmAccountUpdate');

Route::get('createnewuser', 'firmadmin\FirmDashboardController@createnewuser')->name('firm.createnewuser');

Route::get('payment_method', 'firmadmin\FirmDashboardController@payment_method')->name('firm.payment_method');

Route::get('payment_method2', 'firmadmin\FirmDashboardController@payment_method2')->name('firm.payment_method2');

Route::get('create_charge', 'firmadmin\FirmDashboardController@create_charge')->name('firm.create_charge');

Route::get('createclient', 'firmadmin\FirmDashboardController@createclient')->name('firm.createclient');

Route::get('payment_succcess', 'firmadmin\FirmDashboardController@payment_succcess')->name('firm.payment_succcess');

Route::get('schedule_training', 'firmadmin\FirmDashboardController@schedule_training')->name('firm.schedule_training');

Route::get('training_scheduled', 'firmadmin\FirmDashboardController@training_scheduled')->name('firm.training_scheduled');