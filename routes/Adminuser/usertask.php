<?php
	Route::get('usertask', 'adminuser\UserTaskController@index')->name('usertask');
	
	Route::get('usertask/getData', 'adminuser\UserTaskController@getData')->name('usertask.getData');

	Route::get('usertask/task_details/{id}', 'adminuser\UserTaskController@task_details')->name('usertask.task_details');

	Route::get('usertask/edittask/{id}', 'adminuser\UserTaskController@edittask')->name('usertask.edittask');

	Route::post('usertask/update_task2', 'adminuser\UserTaskController@update_task2')->name('usertask.update_task2');
	
	Route::get('usertask/overview/{id}', 'adminuser\UserTaskController@overview')->name('usertask.overview');

	Route::get('usertask/profile/{id}', 'adminuser\UserTaskController@profile')->name('usertask.profile');

	Route::get('usertask/family/{id}', 'adminuser\UserTaskController@family')->name('usertask.family');

	Route::get('usertask/tasks/{id}', 'adminuser\UserTaskController@tasks')->name('usertask.tasks');

	Route::get('usertask/add_new_task/{id}', 'adminuser\UserTaskController@add_new_task')->name('usertask.add_new_task');

	Route::post('usertask/insert_task', 'adminuser\UserTaskController@insert_task')->name('usertask.insert_task');

	Route::get('usertask/edit_case_task/{id}/{tid}', 'adminuser\UserTaskController@edit_case_task')->name('usertask.edit_case_task');

	Route::post('usertask/update_case_task', 'adminuser\UserTaskController@update_case_task')->name('usertask.update_case_task');

	Route::get('usertask/documents/{id}', 'adminuser\UserTaskController@documents')->name('usertask.documents');

	Route::get('usertask/editrequestdocuments/{id}/{did}', 'adminuser\UserTaskController@editrequestdocuments')->name('usertask.editrequestdocuments');

	Route::post('usertask/updaterequestdocuments', 'adminuser\UserTaskController@updaterequestdocuments')->name('usertask.updaterequestdocuments');

	Route::post('usertask/setCaseDocument5', 'adminuser\UserTaskController@setCaseDocument5')->name('usertask.setCaseDocument5');

	Route::get('usertask/notes/{id}', 'adminuser\UserTaskController@notes')->name('usertask.notes');
	
	Route::get('usertask/deletenote/{id}/{tid}', 'adminuser\UserTaskController@deletenote')->name('usertask.deletenote');

	Route::post('usertask/add_new_notes', 'adminuser\UserTaskController@add_new_notes')->name('usertask.add_new_notes');

	Route::get('usertask/caseinbox/{id}', 'adminuser\UserTaskController@caseinbox')->name('usertask.caseinbox');

	Route::get('usertask/getMessageData/{id}', 'adminuser\UserTaskController@getMessageData')->name('usertask.getMessageData');

	Route::post('usertask/sendtextmsg', 'adminuser\UserTaskController@sendtextmsg')->name('usertask.sendtextmsg');

	Route::get('usertask/caseforms/{id}', 'adminuser\UserTaskController@caseforms')->name('usertask.caseforms');
	
	Route::get('usertask/caseforms/{id}/{uid}', 'adminuser\UserTaskController@caseforms')->name('usertask.caseforms');

	Route::get('usertask/readytoreview/{id}', 'adminuser\UserTaskController@readytoreview')->name('usertask.readytoreview');

	Route::get('usertask/casefamily/{id}', 'adminuser\UserTaskController@casefamily')->name('usertask.casefamily');

	Route::get('usertask/addcasefamily/{id}', 'adminuser\UserTaskController@addcasefamily')->name('usertask.addcasefamily');

	Route::get('usertask/addcasefamilymember/{id}', 'adminuser\UserTaskController@addcasefamilymember')->name('usertask.addcasefamilymember');

	Route::post('usertask/addfamilyincase', 'adminuser\UserTaskController@addfamilyincase')->name('usertask.addfamilyincase');

	Route::post('usertask/addfamilymemberincase', 'adminuser\UserTaskController@addfamilymemberincase')->name('usertask.addfamilymemberincase');

	Route::post('usertask/createcasefamily', 'adminuser\UserTaskController@createcasefamily')->name('usertask.createcasefamily');

	Route::get('usertask/familyforms/{tid}/{id}', 'adminuser\UserTaskController@familyforms')->name('usertask.familyforms');

	Route::get('usertask/addfamilyforms/{tid}/{id}', 'adminuser\UserTaskController@addfamilyforms')->name('usertask.addfamilyforms');

	Route::post('usertask/createfamilyforms', 'adminuser\UserTaskController@createfamilyforms')->name('usertask.createfamilyforms');
	
	Route::post('usertask/getFamilyForms', 'adminuser\UserTaskController@getFamilyForms')->name('usertask.getFamilyForms');

	Route::get('usertask/familydocuments/{tid}/{id}', 'adminuser\UserTaskController@familydocuments')->name('usertask.familydocuments');

	Route::get('usertask/additional_service/{id}', 'adminuser\UserTaskController@additional_service')->name('usertask.additional_service');

	Route::post('usertask/request_additional_service', 'adminuser\UserTaskController@request_additional_service')->name('usertask.request_additional_service');

	Route::post('usertask/addderivativeincase', 'adminuser\UserTaskController@addderivativeincase')->name('usertask.addderivativeincase');

	Route::post('usertask/createderivativeincase', 'adminuser\UserTaskController@createderivativeincase')->name('usertask.createderivativeincase');

	Route::get('usertask/addcaseinterpreter/{id}', 'adminuser\UserTaskController@addcaseinterpreter')->name('usertask.addcaseinterpreter');

	Route::get('usertask/case_affidavit/{id}', 'adminuser\UserTaskController@case_affidavit')->name('usertask.case_affidavit');

	Route::post('usertask/uploadaffidavitdocuments', 'adminuser\UserTaskController@uploadaffidavitdocuments')->name('usertask.uploadaffidavitdocuments');

	Route::get('usertask/addcasepetitioner/{id}', 'adminuser\UserTaskController@addcasepetitioner')->name('usertask.addcasepetitioner');

	Route::post('usertask/updateformstatus', 'adminuser\UserTaskController@updateformstatus')->name('usertask.updateformstatus');

	Route::get('usertask/editfamily/{id}/{fid}', 'adminuser\UserTaskController@editfamily')->name('usertask.editfamily');

	Route::get('usertask/createtask', 'adminuser\UserTaskController@createtask')->name('usertask.createtask');

	Route::post('usertask/insertusertask', 'adminuser\UserTaskController@insertusertask')->name('usertask.insertusertask');

	Route::post('usertask/rquestblueprintdocuments', 'adminuser\UserTaskController@rquestblueprintdocuments')->name('usertask.rquestblueprintdocuments');

	Route::get('usertask/uploaddocuments/{id}/{did}', 'adminuser\UserTaskController@uploaddocuments')->name('usertask.uploaddocuments');

	Route::post('usertask/upload_req_doc', 'adminuser\UserTaskController@upload_req_doc')->name('usertask.upload_req_doc');