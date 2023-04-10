<?php
	Route::get('firm', 'admin\FirmController@index')->name('firm');
	Route::get('firm/create', 'admin\FirmController@create')->name('firm.create');
	Route::get('firm/getData', 'admin\FirmController@getData')->name('firm.getData');
	Route::get('firm/getFirmCaseData', 'admin\FirmController@getFirmCaseData')->name('firm.getFirmCaseData');
	Route::get('firm/getFirmVPCaseData', 'admin\FirmController@getFirmVPCaseData')->name('firm.getFirmVPCaseData');
	Route::get('firm/timeline/{id}', 'admin\FirmController@timeline')->name('firm.timeline');
	Route::get('firm/reactive/{id}', 'admin\FirmController@reactive')->name('firm.reactive');
	Route::get('firm/caseIsConform/{id}', 'admin\FirmController@caseIsConform')->name('firm.caseIsConform');
	Route::get('case_conformation/{id}', 'admin\FirmController@case_conformation')->name('case_conformation');
	Route::get('firm/delete/{id}', 'admin\FirmController@delete')->name('firm.delete');
	Route::get('firm/firm_edit/{id}', 'admin\FirmController@firm_edit')->name('firm.firm_edit');
	Route::get('firm/firm_details/{id}', 'admin\FirmController@firm_details')->name('firm.firm_details');
	Route::get('firm/firm_users/{id}', 'admin\FirmController@firm_users')->name('firm.firm_users');
	Route::get('firm/firm_billing/{id}', 'admin\FirmController@firm_billing')->name('firm.firm_billing');
	Route::get('firm/firm_cases/{id}', 'admin\FirmController@firm_cases')->name('firm.firm_cases');
	Route::get('firm/viewclient/{id}/{cid}', 'admin\FirmController@viewclient')->name('firm.viewclient');
	Route::get('firm/firm_vpcases/{id}', 'admin\FirmController@firm_vpcases')->name('firm.firm_vpcases');
	Route::post('firm/update_firm', 'admin\FirmController@update_firm')->name('firm.update_firm');
	Route::post('firm/create_firm', 'admin\FirmController@create_firm')->name('firm.create_firm');
	Route::get('firm/get_firmuser_data', 'admin\FirmController@get_firmuser_data')->name('firm.get_firmuser_data');

	Route::get('firm/firm_notes/{id}', 'admin\FirmController@firm_notes')->name('firm.firm_notes');

	Route::get('firm/get_firmnote_data', 'admin\FirmController@get_firmnote_data')->name('firm.get_firmnote_data');

	Route::post('firm/add_firm_notes', 'admin\FirmController@add_firm_notes')->name('firm.add_firm_notes');

	Route::get('firm/delete_firm_note/{id}', 'admin\FirmController@delete_firm_note')->name('firm.delete_firm_note');
