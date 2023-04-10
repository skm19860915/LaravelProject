<?php	
	//Route::get('admindashboard', 'firmadmin\FirmDashboardController@index')->name('admindashboard');

// Route::post('admindashboard/search', 'firmadmin\FirmDashboardController@search')->name('admindashboard.search');

// Route::post('admindashboard/searchshow', 'firmadmin\FirmDashboardController@searchshow')->name('admindashboard.searchshow');

Route::get('billing', 'firmadmin\FirmDashboardController@billing')->name('billing');

Route::get('billing/getBillingData', 'firmadmin\FirmDashboardController@getBillingData')->name('billing.getBillingData');

Route::post('billing/create_invoice', 'firmadmin\FirmDashboardController@create_invoice')->name('billing.create_invoice');

Route::get('billing/invoice', 'firmadmin\FirmDashboardController@invoice')->name('billing.invoice');

Route::get('billing/getInvoiceData', 'firmadmin\FirmDashboardController@getInvoiceData')->name('billing.getInvoiceData');

Route::get('billing/invoice/create/{id}', 'firmadmin\FirmDashboardController@create')->name('billing.create');

Route::get('billing/invoice/edit_invoice/{id}', 'firmadmin\FirmDashboardController@edit_invoice')->name('billing.edit_invoice');

Route::post('billing/invoice/update_invoice', 'firmadmin\FirmDashboardController@update_invoice')->name('billing.update_invoice');

Route::get('billing/invoice/paid_invoice/{id}', 'firmadmin\FirmDashboardController@paid_invoice')->name('billing.paid_invoice');

Route::get('billing/invoice/unpaid_invoice/{id}', 'firmadmin\FirmDashboardController@unpaid_invoice')->name('billing.unpaid_invoice');

Route::get('billing/invoice/cancel_invoice/{id}', 'firmadmin\FirmDashboardController@cancel_invoice')->name('billing.cancel_invoice');

Route::get('billing/scheduled', 'firmadmin\FirmDashboardController@scheduled')->name('billing.scheduled');

Route::get('billing/acceptpayment', 'firmadmin\FirmDashboardController@acceptpayment')->name('billing.acceptpayment');

Route::get('billing/getScheduledInvoiceData', 'firmadmin\FirmDashboardController@getScheduledInvoiceData')->name('billing.getScheduledInvoiceData');

Route::get('exit_payment_page', 'firmadmin\FirmDashboardController@exit_payment_page')->name('exit_payment_page');

Route::post('payForUser', 'firmadmin\FirmDashboardController@payForUser')->name('payForUser');

Route::get('transactions', 'firmadmin\FirmDashboardController@transactions')->name('transactions');

Route::get('getFirmTransactions', 'firmadmin\FirmDashboardController@getFirmTransactions')->name('getFirmTransactions');

Route::post('pay_for_cms', 'firmadmin\FirmDashboardController@pay_for_cms')->name('pay_for_cms');

Route::get('upgradetocms', 'firmadmin\FirmDashboardController@upgradetocms')->name('upgradetocms');

Route::get('request_to_delete', 'firmadmin\FirmDashboardController@request_to_delete')->name('request_to_delete');

