<?php

Route::get('lead/emailtemplatesshow', 'firmadmin\FirmLeadController@emailtemplatesshow')->name('lead.emailtemplatesshow');
/*Route::get('lead/getData', 'FirmLeadController@getData')->name('lead.getData');*/
Route::get('lead', 'firmadmin\FirmLeadController@index')->name('lead');
Route::get('lead/create', 'firmadmin\FirmLeadController@create')->name('lead.create');
Route::get('lead/create_event/{id}', 'firmadmin\FirmLeadController@create_event')->name('lead.create_event');

Route::get('lead/view_event/{id}', 'firmadmin\FirmLeadController@view_event')->name('lead.view_event');

Route::get('lead/create_client/{id}', 'firmadmin\FirmLeadController@create_client')->name('lead.create_client');

Route::post('lead/convert_client', 'firmadmin\FirmLeadController@convert_client')->name('lead.convert_client');

Route::get('lead/getData', 'firmadmin\FirmLeadController@getData')->name('lead.getData');

Route::get('lead/delete/{id}', 'firmadmin\FirmLeadController@delete')->name('lead.delete');

Route::get('lead/lost/{id}', 'firmadmin\FirmLeadController@lost')->name('lead.lost');

Route::get('lead/edit/{id}', 'firmadmin\FirmLeadController@edit')->name('lead.edit');

Route::post('lead/update_lead', 'firmadmin\FirmLeadController@update_lead')->name('lead.update_lead');

Route::post('lead/create_lead', 'firmadmin\FirmLeadController@create_lead')->name('lead.create_lead');
Route::post('lead/create_lead_event', 'firmadmin\FirmLeadController@create_lead_event')->name('lead.create_lead_event');

Route::get('lead/show/{id}', 'firmadmin\FirmLeadController@show')->name('lead.show');

Route::get('lead/billing/{id}', 'firmadmin\FirmLeadController@billing')->name('lead.billing');

Route::get('lead/invoice/{id}', 'firmadmin\FirmLeadController@invoice')->name('lead.invoice');

Route::get('lead/add_invoice/{id}', 'firmadmin\FirmLeadController@add_invoice')->name('lead.add_invoice');

Route::get('lead/acceptpayment/{id}', 'firmadmin\FirmLeadController@acceptpayment')->name('lead.acceptpayment');

Route::get('lead/acceptpayment/{id}/{id1}', 'firmadmin\FirmLeadController@acceptpayment')->name('lead.acceptpayment');

Route::post('lead/create_lead_invoice', 'firmadmin\FirmLeadController@create_lead_invoice')->name('lead.create_lead_invoice');

Route::get('lead/scheduled/{id}', 'firmadmin\FirmLeadController@scheduled')->name('lead.scheduled');

Route::get('lead/scheduled/{id}/{id1}', 'firmadmin\FirmLeadController@scheduled')->name('lead.scheduled');

Route::post('lead/SchedulePayment', 'firmadmin\FirmLeadController@SchedulePayment')->name('lead.SchedulePayment');

Route::get('lead/view_invoice/{id}/{id1}', 'firmadmin\FirmLeadController@view_invoice')->name('lead.view_invoice');

Route::get('lead/edit_invoice/{id}/{id1}', 'firmadmin\FirmLeadController@edit_invoice')->name('lead.edit_invoice');

Route::post('lead/update_lead_invoice', 'firmadmin\FirmLeadController@update_lead_invoice')->name('lead.update_lead_invoice');

Route::get('lead/cancel_invoice/{id}/{id1}', 'firmadmin\FirmLeadController@cancel_invoice')->name('lead.cancel_invoice');

Route::get('lead/schedule_history/{id}', 'firmadmin\FirmLeadController@schedule_history')->name('lead.schedule_history');

Route::get('lead/undo_lead/{id}', 'firmadmin\FirmLeadController@undo_lead')->name('lead.undo_lead');

Route::get('lead/notes/{id}', 'firmadmin\FirmLeadController@notes')->name('lead.notes');
Route::post('lead/create_lead_note', 'firmadmin\FirmLeadController@create_lead_note')->name('lead.create_lead_note');
    /*Route::get('users/edit/{id}', 'FirmUserController@edit')->name('users.edit');
    Route::post('users/update', 'FirmUserController@update')->name('users.update');*/