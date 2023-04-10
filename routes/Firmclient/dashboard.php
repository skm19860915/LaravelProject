<?php	
	Route::get('clientdashboard', 'firmclient\FirmclientDashboardController@index')->name('clientdashboard');
	Route::get('clientcase', 'firmclient\FirmclientDashboardController@clientcase')->name('clientcase');
	Route::get('clientcase/getClientCaseData', 'firmclient\FirmclientDashboardController@getClientCaseData')->name('getClientCaseData');
	Route::get('clientcase/show/{id}', 'firmclient\FirmclientDashboardController@show')->name('show');
	Route::get('clientcase/casetasks/{id}', 'firmclient\FirmclientDashboardController@casetasks')->name('casetasks');
	Route::get('clientcase/add_casetasks/{id}', 'firmclient\FirmclientDashboardController@add_casetasks')->name('add_casetasks');
	Route::post('clientcase/insert_newtask', 'firmclient\FirmclientDashboardController@insert_newtask')->name('insert_newtask');
	Route::get('clientcase/casenotes/{id}', 'firmclient\FirmclientDashboardController@casenotes')->name('casenotes');
	Route::get('clientcase/casefamily/{id}', 'firmclient\FirmclientDashboardController@casefamily')->name('casefamily');
	Route::get('caseuser', 'firmclient\FirmclientDashboardController@caseuser')->name('caseuser');
	Route::get('firmclient/billing/invoice', 'firmclient\FirmclientDashboardController@invoice')->name('firmclient.billing.invoice');

	Route::get('firmclient/billing/getInvoiceData', 'firmclient\FirmclientDashboardController@getInvoiceData')->name('firmclient.billing.getInvoiceData');

	Route::post('firmclient/billing/payForInvoice', 'firmclient\FirmclientDashboardController@payForInvoice')->name('firmclient.billing.payForInvoice');

	Route::post('firmclient/billing/payForInvoice1', 'firmclient\FirmclientDashboardController@payForInvoice1')->name('firmclient.billing.payForInvoice1');

	Route::get('firmclient/billing/invoice/viewinvoice/{id}', 'firmclient\FirmclientDashboardController@viewinvoice')->name('firmclient.billing.viewinvoice');

	Route::get('clientcase/questionnaire', 'firmclient\FirmclientDashboardController@questionnaire')->name('clientcase.questionnaire');

	Route::get('clientcase/case_documents', 'firmclient\FirmclientDashboardController@case_documents')->name('clientcase.case_documents');

	Route::get('complete_task/{tid}', 'firmclient\FirmclientDashboardController@complete_task')->name('clientcase.complete_task');

	Route::get('mybalance', 'firmclient\FirmclientDashboardController@mybalance')->name('mybalance');
