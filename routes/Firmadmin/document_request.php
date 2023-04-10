<?php
	Route::get('document_request/{id}', 'firmadmin\DocumentRequestController@document_request')->name('document_request');
	Route::get('document_request/upload_document/{id}', 'firmadmin\DocumentRequestController@upload_document')->name('upload_document');
	Route::get('document_request/getDataDocument/{id}', 'firmadmin\DocumentRequestController@getDataDocument')->name('document_request.getDataDocument');
	Route::get('document_request/client_Cases/{id}', 'firmadmin\DocumentRequestController@client_Cases')->name('document_request.client_Cases');
	Route::post('document_request/setDataDocument1', 'firmadmin\DocumentRequestController@setDataDocument1')->name('document_request.setDataDocument1');
	Route::post('document_request/setClientDataDocument1', 'firmadmin\DocumentRequestController@setClientDataDocument1')->name('document_request.setClientDataDocument1');
	Route::get('document_request/completeDocument/{id}', 'firmadmin\DocumentRequestController@completeDocument')->name('document_request.completeDocument');
	Route::get('document_request/Request_Quote/{id}', 'firmadmin\DocumentRequestController@Request_Quote')->name('document_request.Request_Quote');
	Route::get('document_request/pay_for_translation/{id}', 'firmadmin\DocumentRequestController@pay_for_translation')->name('document_request.pay_for_translation');
	// Route::get('userclient/show/{id}', 'firmadmin\UserClientController@show')->name('userclient.show');