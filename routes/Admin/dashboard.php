<?php
	Route::get('dashboard', 'DashboardController')->name('dashboard');
	Route::get('adminbilling', 'DashboardController@adminbilling')->name('adminbilling');
	Route::get('getAdminBillingData', 'DashboardController@getAdminBillingData')->name('getAdminBillingData');
	Route::get('markasread', 'DashboardController@markasread')->name('markasread');
