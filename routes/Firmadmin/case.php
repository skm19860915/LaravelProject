<?php
/*Route::get('client/getData', 'FirmClientController@getData')->name('client.getData');*/
Route::get('case', 'firmadmin\FirmCaseController@index')->name('case');
Route::get('case/mycase', 'firmadmin\FirmCaseController@mycase')->name('case.mycase');
Route::get('case/allcase', 'firmadmin\FirmCaseController@allcase')->name('case.allcase');
Route::get('case/create', 'firmadmin\FirmCaseController@create')->name('case.create');
Route::get('case/getData', 'firmadmin\FirmCaseController@getData')->name('case.getData');
Route::get('case/delete/{id}', 'firmadmin\FirmCaseController@delete')->name('case.delete');
Route::post('case/create_case', 'firmadmin\FirmCaseController@create_case')->name('case.create_case');
Route::get('case/show/{id}', 'firmadmin\FirmCaseController@show')->name('case.show');
Route::get('case/profile/{id}', 'firmadmin\FirmCaseController@profile')->name('case.profile');
Route::get('case/create_task/{id}', 'firmadmin\FirmCaseController@create_task')->name('case.create_task');
Route::get('case/case_tasks/{id}', 'firmadmin\FirmCaseController@case_tasks')->name('case.case_tasks');
Route::get('case/add_case_tasks/{id}', 'firmadmin\FirmCaseController@add_case_tasks')->name('case.add_case_tasks');
Route::get('case/edit_case_tasks/{id}/{tid}', 'firmadmin\FirmCaseController@edit_case_tasks')->name('case.edit_case_tasks');
Route::post('case/insert_new_task', 'firmadmin\FirmCaseController@insert_new_task')->name('case.insert_new_task');
Route::post('case/update_case_task', 'firmadmin\FirmCaseController@update_case_task')->name('case.update_case_task');
Route::get('case/case_notes/{id}', 'firmadmin\FirmCaseController@case_notes')->name('case.case_notes');
Route::get('case/case_documents/{id}', 'firmadmin\FirmCaseController@case_documents')->name('case.case_documents');
Route::get('case/upload_documents/{id}/{fid}', 'firmadmin\FirmCaseController@upload_documents')->name('case.upload_documents');
Route::post('case/setDataDocument4', 'firmadmin\FirmCaseController@setDataDocument4')->name('case.setDataDocument4');
Route::get('case/upload_family_documents/{id}/{fid}', 'firmadmin\FirmCaseController@upload_family_documents')->name('case.upload_family_documents');
Route::post('case/setFamilyDocument4', 'firmadmin\FirmCaseController@setFamilyDocument4')->name('case.setFamilyDocument4');
Route::post('case/add_case_notes', 'firmadmin\FirmCaseController@add_case_notes')->name('case.add_case_notes');
Route::get('case/delete_note/{id}', 'firmadmin\FirmCaseController@delete_note')->name('case.delete_note');
Route::post('case/add_task', 'firmadmin\FirmCaseController@add_task')->name('case.add_task');
Route::get('case/edit/{id}', 'firmadmin\FirmCaseController@edit')->name('case.edit');
Route::post('case/update_case', 'firmadmin\FirmCaseController@update_case')->name('case.update_case');
Route::get('case/create_event/{id}', 'firmadmin\FirmCaseController@create_event')->name('case.create_event');
Route::get('case/case_event/{id}', 'firmadmin\FirmCaseController@case_event')->name('case.case_event');
Route::post('case/create_case_event', 'firmadmin\FirmCaseController@create_case_event')->name('case.create_case_event');

Route::get('case/case_complete/{id}', 'firmadmin\FirmCaseController@case_complete')->name('case.case_complete');

Route::get('case/case_complete1/{id}', 'firmadmin\FirmCaseController@case_complete1')->name('case.case_complete1');

Route::get('case/case_incomplete/{id}', 'firmadmin\FirmCaseController@case_incomplete')->name('case.case_incomplete');

Route::post('case/update_court_date', 'firmadmin\FirmCaseController@update_court_date')->name('case.update_court_date');

Route::get('case/case_document/{id}', 'firmadmin\FirmCaseController@case_document')->name('case.case_document');

Route::get('case/getCaseDataDocument/{id}', 'firmadmin\FirmCaseController@getCaseDataDocument')->name('case.getCaseDataDocument');

Route::get('case/getFamilyDataDocument/{id}/{fid}', 'firmadmin\FirmCaseController@getFamilyDataDocument')->name('case.getFamilyDataDocument');

Route::post('case/setCaseDataDocument', 'firmadmin\FirmCaseController@setCaseDataDocument')->name('case.setCaseDataDocument');

Route::get('case/Case_Request_Quote/{id}', 'firmadmin\FirmCaseController@Case_Request_Quote')->name('case.Case_Request_Quote');

Route::get('case/Case_Family_Request_Quote/{id}/{fid}', 'firmadmin\FirmCaseController@Case_Family_Request_Quote')->name('case.Case_Family_Request_Quote');

Route::get('case/pay_case_translation/{id}', 'firmadmin\FirmCaseController@pay_case_translation')->name('case.pay_case_translation');

Route::get('case/case_forms/{id}', 'firmadmin\FirmCaseController@case_forms')->name('case.case_forms');

Route::get('case/case_forms/{id}/{uid}', 'firmadmin\FirmCaseController@case_forms')->name('case.case_forms');

Route::get('case/add_forms/{id}', 'firmadmin\FirmCaseController@add_forms')->name('case.add_forms');

Route::post('case/create_case_form', 'firmadmin\FirmCaseController@create_case_form')->name('case.create_case_form');

Route::post('case/setCaseDocument', 'firmadmin\FirmCaseController@setCaseDocument')->name('case.setCaseDocument');

Route::get('case/case_family/{id}', 'firmadmin\FirmCaseController@case_family')->name('case.case_family');

Route::get('case/add_case_family/{id}', 'firmadmin\FirmCaseController@add_case_family')->name('case.add_case_family');

Route::get('case/add_case_petitioner/{id}', 'firmadmin\FirmCaseController@add_case_petitioner')->name('case.add_case_petitioner');

Route::post('case/create_case_family', 'firmadmin\FirmCaseController@create_case_family')->name('case.create_case_family');

Route::get('case/view_family_forms/{id}/{fid}', 'firmadmin\FirmCaseController@view_family_forms')->name('case.view_family_forms');

Route::get('case/view_family_documents/{id}/{fid}', 'firmadmin\FirmCaseController@view_family_documents')->name('case.view_family_documents');

Route::get('case/add_case_family_forms/{id}/{fid}', 'firmadmin\FirmCaseController@add_case_family_forms')->name('case.add_case_family_forms');

Route::post('case/create_family_forms', 'firmadmin\FirmCaseController@create_family_forms')->name('case.create_family_forms');

Route::get('case/add_case_family_member/{id}', 'firmadmin\FirmCaseController@add_case_family_member')->name('case.add_case_family_member');

Route::get('case/additional_service/{id}', 'firmadmin\FirmCaseController@additional_service')->name('case.additional_service');

Route::post('case/pay_additional_service', 'firmadmin\FirmCaseController@pay_additional_service')->name('case.pay_additional_service');

Route::post('case/add_family_incase', 'firmadmin\FirmCaseController@add_family_incase')->name('case.add_family_incase');

Route::post('case/add_family_member_incase', 'firmadmin\FirmCaseController@add_family_member_incase')->name('case.add_family_member_incase');

Route::post('case/add_derivative_incase', 'firmadmin\FirmCaseController@add_derivative_incase')->name('case.add_derivative_incase');

Route::post('case/create_derivative_incase', 'firmadmin\FirmCaseController@create_derivative_incase')->name('case.create_derivative_incase');

Route::get('case/add_case_interpreter/{id}', 'firmadmin\FirmCaseController@add_case_interpreter')->name('case.add_case_interpreter');

Route::get('case/affidavit/{id}', 'firmadmin\FirmCaseController@affidavit')->name('case.affidavit');

Route::post('case/upload_affidavit_documents', 'firmadmin\FirmCaseController@upload_affidavit_documents')->name('case.upload_affidavit_documents');

Route::get('case/edit_family/{id}/{fid}', 'firmadmin\FirmCaseController@edit_family')->name('case.edit_family');

Route::get('case/case_inbox/{id}', 'firmadmin\FirmCaseController@case_inbox')->name('case.case_inbox');

Route::post('case/rquest_blueprint_documents', 'firmadmin\FirmCaseController@rquest_blueprint_documents')->name('case.rquest_blueprint_documents');
Route::post('case/requestadditionalservice', 'firmadmin\FirmCaseController@requestadditionalservice')->name('case.requestadditionalservice');