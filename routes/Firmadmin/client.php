<?php

Route::get('client', 'firmadmin\FirmNewClientController@index')->name('client');
Route::get('client/create', 'firmadmin\FirmNewClientController@create')->name('client.create');
Route::get('client/getData', 'firmadmin\FirmNewClientController@getData')->name('client.getData');
Route::get('client/delete/{id}', 'firmadmin\FirmNewClientController@delete')->name('client.delete');
Route::post('client/create_client', 'firmadmin\FirmNewClientController@create_client')->name('client.create_client');

Route::get('client/family/{id}', 'firmadmin\FirmNewClientController@family')->name('client.family');

Route::get('client/family/{id}/{ids}', 'firmadmin\FirmNewClientController@family')->name('client.family');

Route::post('client/add_family', 'firmadmin\FirmNewClientController@add_family')->name('client.add_family');

Route::get('client/view_family/{id}', 'firmadmin\FirmNewClientController@view_family')->name('client.view_family');


Route::get('client/notes/{id}', 'firmadmin\FirmNewClientController@notes')->name('client.notes');

Route::post('client/add_notes', 'firmadmin\FirmNewClientController@add_notes')->name('client.add_notes');

Route::get('client/view_notes/{id}', 'firmadmin\FirmNewClientController@view_notes')->name('client.view_notes');

Route::get('client/show/{id}', 'firmadmin\FirmNewClientController@show')->name('client.show');

Route::get('client/edit/{id}', 'firmadmin\FirmNewClientController@edit')->name('client.edit');

Route::post('client/update', 'firmadmin\FirmNewClientController@update')->name('client.update');

Route::get('client/client_files/{id}', 'firmadmin\FirmNewClientController@client_files')->name('client.client_files');

Route::post('client/create_client_file', 'firmadmin\FirmNewClientController@create_client_file')->name('client.create_client_file');

Route::post('client/update_client_file', 'firmadmin\FirmNewClientController@update_client_file')->name('client.update_client_file');

Route::get('client/create_event/{id}', 'firmadmin\FirmNewClientController@create_event')->name('client.create_event');

Route::post('client/create_client_event', 'firmadmin\FirmNewClientController@create_client_event')->name('client.create_client_event');

Route::get('client/client_document/{id}', 'firmadmin\FirmNewClientController@client_document')->name('client.client_document');

Route::get('client/getClienDocument/{id}', 'firmadmin\FirmNewClientController@getClienDocument')->name('client.getClienDocument');

Route::post('client/setClientDocument', 'firmadmin\FirmNewClientController@setClientDocument')->name('client.setClientDocument');

Route::get('client/profile/{id}', 'firmadmin\FirmNewClientController@profile')->name('client.profile');

Route::get('client/client_task/{id}', 'firmadmin\FirmNewClientController@client_task')->name('client.client_task');

Route::get('client/add_client_task/{id}', 'firmadmin\FirmNewClientController@add_client_task')->name('client.add_client_task');

Route::get('client/edit_client_task/{id}/{tid}', 'firmadmin\FirmNewClientController@edit_client_task')->name('client.edit_client_task');

Route::post('client/insert_client_task', 'firmadmin\FirmNewClientController@insert_client_task')->name('client.insert_client_task');

Route::post('client/update_client_task', 'firmadmin\FirmNewClientController@update_client_task')->name('client.update_client_task');

Route::get('client/client_event/{id}', 'firmadmin\FirmNewClientController@client_event')->name('client.client_event');

Route::get('client/client_edit_event/{id}/{eid}', 'firmadmin\FirmNewClientController@client_edit_event')->name('client.client_edit_event');

Route::get('client/client_case/{id}', 'firmadmin\FirmNewClientController@client_case')->name('client.client_case');

Route::get('client/add_new_case/{id}', 'firmadmin\FirmNewClientController@add_new_case')->name('client.add_new_case');

Route::post('client/create_client_case', 'firmadmin\FirmNewClientController@create_client_case')->name('client.create_client_case');

Route::get('client/document/{id}', 'firmadmin\FirmNewClientController@document')->name('client.document');

Route::get('client/client_billing/{id}', 'firmadmin\FirmNewClientController@client_billing')->name('client.client_billing');

Route::get('client/client_invoice/{id}', 'firmadmin\FirmNewClientController@client_invoice')->name('client.client_invoice');

Route::get('client/client_scheduled/{id}', 'firmadmin\FirmNewClientController@client_scheduled')->name('client.client_scheduled');

Route::get('client/client_scheduled/{id}/{id1}', 'firmadmin\FirmNewClientController@client_scheduled')->name('client.client_scheduled');

Route::get('client/client_acceptpayment/{id}', 'firmadmin\FirmNewClientController@client_acceptpayment')->name('client.client_acceptpayment');

Route::get('client/client_schedule_history/{id}', 'firmadmin\FirmNewClientController@client_schedule_history')->name('client.client_schedule_history');

Route::get('client/client_acceptpayment/{id}/{id1}', 'firmadmin\FirmNewClientController@client_acceptpayment')->name('client.client_acceptpayment');

Route::get('client/view_client_invoice/{id}', 'firmadmin\FirmNewClientController@view_client_invoice')->name('client.view_client_invoice');

Route::get('client/edit_client_invoice/{id}', 'firmadmin\FirmNewClientController@edit_client_invoice')->name('client.edit_client_invoice');

Route::post('client/update_client_invoice', 'firmadmin\FirmNewClientController@update_client_invoice')->name('client.update_client_invoice');

Route::get('client/add_new_invoice/{id}', 'firmadmin\FirmNewClientController@add_new_invoice')->name('client.add_new_invoice');

Route::post('client/create_client_invoice', 'firmadmin\FirmNewClientController@create_client_invoice')->name('client.create_client_invoice');

Route::get('client/cancel_client_invoice/{id}/{cid}', 'firmadmin\FirmNewClientController@cancel_client_invoice')->name('client.cancel_client_invoice');

Route::get('client/edit_family/{id}/{fid}', 'firmadmin\FirmNewClientController@edit_family')->name('client.edit_family');

Route::get('client/delete_family/{id}/{fid}', 'firmadmin\FirmNewClientController@delete_family')->name('client.delete_family');

Route::post('client/payForClientInvoice', 'firmadmin\FirmNewClientController@payForClientInvoice')->name('client.payForClientInvoice');

Route::post('client/ClientSchedulePayment', 'firmadmin\FirmNewClientController@ClientSchedulePayment')->name('client.ClientSchedulePayment');

Route::get('client/GetClientSchedulePayment/{id}/{id1}', 'firmadmin\FirmNewClientController@GetClientSchedulePayment')->name('client.GetClientSchedulePayment');

Route::get('client/SkipPayment/{id}', 'firmadmin\FirmNewClientController@SkipPayment')->name('client.SkipPayment');

Route::post('client/UpdateScheduleCard', 'firmadmin\FirmNewClientController@UpdateScheduleCard')->name('client.UpdateScheduleCard');

Route::get('client/delete_doc/{id}/{cid}', 'firmadmin\FirmNewClientController@delete_doc')->name('client.delete_doc');

Route::post('client/portal_access', 'firmadmin\FirmNewClientController@portal_access')->name('client.portal_access');