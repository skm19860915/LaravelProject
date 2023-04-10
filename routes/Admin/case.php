<?php 
Route::get('allcases', 'admin\CaseController@allcases')->name('allcases');
Route::get('allcases/getCaseData', 'admin\CaseController@getCaseData')->name('allcases.getCaseData');
Route::get('allcases/show/{id}', 'admin\CaseController@show')->name('allcases.show');
Route::get('allcases/profile/{id}', 'admin\CaseController@profile')->name('allcases.profile');
Route::get('allcases/edit/{id}', 'admin\CaseController@edit')->name('allcases.edit');
Route::post('allcases/update', 'admin\CaseController@update')->name('allcases.update');
Route::get('allcases/casefamily/{id}', 'admin\CaseController@casefamily')->name('allcases.casefamily');
Route::get('allcases/casetask/{id}', 'admin\CaseController@casetask')->name('allcases.casetask');

Route::get('allcases/caseevent/{id}', 'admin\CaseController@caseevent')->name('allcases.caseevent');
Route::get('allcases/casedocuments/{id}', 'admin\CaseController@casedocuments')->name('allcases.casedocuments');
Route::get('allcases/casenotes/{id}', 'admin\CaseController@casenotes')->name('allcases.casenotes');
Route::get('allcases/caseforms/{id}', 'admin\CaseController@caseforms')->name('allcases.caseforms');
Route::get('allcases/caseforms/{id}/{uid}', 'admin\CaseController@caseforms')->name('allcases.caseforms');
Route::get('allcases/additionalservice/{id}', 'admin\CaseController@additionalservice')->name('allcases.additionalservice');
Route::get('allcases/affidavit/{id}', 'admin\CaseController@affidavit')->name('allcases.affidavit');

Route::get('allcases/getCaseDataDocument/{id}', 'admin\CaseController@getCaseDataDocument')->name('allcases.getCaseDataDocument');

Route::get('allcases/getFamilyDataDocument/{id}/{fid}', 'admin\CaseController@getFamilyDataDocument')->name('allcases.getFamilyDataDocument');
Route::post('allcases/addderivativeincase1', 'admin\CaseController@addderivativeincase1')->name('allcases.addderivativeincase1');
Route::get('allcases/addnewtask/{id}', 'admin\CaseController@addnewtask')->name('allcases.addnewtask');
Route::post('allcases/inserttask', 'admin\CaseController@inserttask')->name('allcases.inserttask');
Route::get('allcases/editcasetask/{id}/{tid}', 'admin\CaseController@editcasetask')->name('allcases.editcasetask');
Route::post('allcases/updatecasetask', 'admin\CaseController@updatecasetask')->name('allcases.updatecasetask');
Route::post('allcases/addnewnotes', 'admin\CaseController@addnewnotes')->name('allcases.addnewnotes');
Route::get('allcases/deletecasenote/{id}/{cid}', 'admin\CaseController@deletecasenote')->name('allcases.deletecasenote');
Route::post('allcases/setAdminCaseDataDocument', 'admin\CaseController@setAdminCaseDataDocument')->name('allcases.setAdminCaseDataDocument');
Route::get('allcases/completeDocument4/{id}', 'admin\CaseController@completeDocument4')->name('allcases.completeDocument4');
Route::post('allcases/setAdminCaseDocument', 'admin\CaseController@setAdminCaseDocument')->name('allcases.setAdminCaseDocument');
Route::post('allcases/uploadaffidavitdocuments4', 'admin\CaseController@uploadaffidavitdocuments4')->name('allcases.uploadaffidavitdocuments4');
Route::post('allcases/requestadditionalservice', 'admin\CaseController@requestadditionalservice')->name('allcases.requestadditionalservice');
Route::post('allcases/rquestblueprintdocuments1', 'admin\CaseController@rquestblueprintdocuments1')->name('allcases.rquestblueprintdocuments1');
Route::get('allcases/editrequestdocuments/{id}/{did}', 'admin\CaseController@editrequestdocuments')->name('allcases.editrequestdocuments');
Route::post('allcases/updaterequestdocuments', 'admin\CaseController@updaterequestdocuments')->name('allcases.updaterequestdocuments');
Route::get('allcases/uploaddocuments/{id}/{did}', 'admin\CaseController@uploaddocuments')->name('allcases.uploaddocuments');
Route::post('allcases/upload_req_doc', 'admin\CaseController@upload_req_doc')->name('allcases.upload_req_doc');
Route::get('allcases/casecomplete1/{id}', 'admin\CaseController@casecomplete1')->name('allcases.casecomplete1');
Route::post('allcases/updatecaseforms', 'admin\CaseController@updatecaseforms')->name('allcases.updatecaseforms');