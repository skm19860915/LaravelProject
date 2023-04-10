<?php
	Route::get('document_request/{id}', 'adminuser\DocumentRequestController@document_request')->name('document_request');
	Route::get('document_request/getDataDocument/{id}', 'adminuser\DocumentRequestController@getDataDocument')->name('document_request.getDataDocument');
	Route::get('document_request/getFamilyDocument/{id}/{fid}', 'adminuser\DocumentRequestController@getFamilyDocument')->name('document_request.getFamilyDocument');
	Route::get('document_request/client_Cases/{id}', 'adminuser\DocumentRequestController@client_Cases')->name('document_request.client_Cases');
	Route::post('document_request/setDataDocument', 'adminuser\DocumentRequestController@setDataDocument')->name('document_request.setDataDocument');
	Route::post('document_request/setDataDocument2', 'adminuser\DocumentRequestController@setDataDocument2')->name('document_request.setDataDocument2');
	Route::get('document_request/completeDocument/{id}', 'adminuser\DocumentRequestController@completeDocument')->name('document_request.completeDocument');
	// Route::get('userclient/show/{id}', 'adminuser\UserClientController@show')->name('userclient.show');