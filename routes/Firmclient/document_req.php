<?php
	Route::get('firmclient/document_requests/{id}', 'firmclient\DocumentRequestController1@document_request')->name('firmclient.document_requests');
	Route::get('firmclient/document_request/getDataDocument1/{id}', 'firmclient\DocumentRequestController1@getDataDocument1')->name('firmclient.document_request.getDataDocument1');
	Route::post('firmclient/document_request/setDataDocument', 'firmclient\DocumentRequestController1@setDataDocument')->name('firmclient.document_request.setDataDocument');
	Route::post('firmclient/document_request/setCaseDocument4', 'firmclient\DocumentRequestController1@setCaseDocument4')->name('firmclient.document_request.setCaseDocument4');


	Route::get('firmclient/family_document_requests/{id}/{fid}', 'firmclient\DocumentRequestController1@family_document_requests')->name('firmclient.family_document_requests');
	Route::get('firmclient/document_request/getFamilyDataDocument1/{id}/{fid}', 'firmclient\DocumentRequestController1@getFamilyDataDocument1')->name('firmclient.document_request.getFamilyDataDocument1');
	Route::post('firmclient/document_request/setFamilyDataDocument', 'firmclient\DocumentRequestController1@setFamilyDataDocument')->name('firmclient.document_request.setFamilyDataDocument');