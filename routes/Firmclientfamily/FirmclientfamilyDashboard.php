<?php	
	Route::get('clientfamilydashboard', 'firmclientfamily\FirmclientfamilyDashboardController@index')->name('clientfamilydashboard');
    Route::get('clientfamilydashboard/familyCases', 'firmclientfamily\FirmclientfamilyDashboardController@Cases')->name('clientfamily.familycases');
    Route::get('clientfamilydashboard/show/{id}', 'firmclientfamily\FirmclientfamilyDashboardController@show')->name('clientfamily.show');
    Route::get('clientfamilydashboard/familytask/{id}', 'firmclientfamily\FirmclientfamilyDashboardController@familytask')->name('clientfamily.familytask');
    Route::get('clientfamilydashboard/addfamilytask/{id}', 'firmclientfamily\FirmclientfamilyDashboardController@addfamilytask')->name('clientfamily.addfamilytask');
    Route::post('clientfamilydashboard/insert_family_task', 'firmclientfamily\FirmclientfamilyDashboardController@insert_family_task')->name('clientfamily.insert_family_task');
    Route::get('clientfamilydashboard/familydocuments/{id}', 'firmclientfamily\FirmclientfamilyDashboardController@familydocuments')->name('clientfamily.familydocuments');
    Route::post('clientfamilydashboard/setCaseFamilyDocument', 'firmclientfamily\FirmclientfamilyDashboardController@setCaseFamilyDocument')->name('clientfamily.setCaseFamilyDocument');
    Route::get('clientfamilydashboard/familynotes/{id}', 'firmclientfamily\FirmclientfamilyDashboardController@familynotes')->name('clientfamily.familynotes');
    Route::post('clientfamilydashboard/add_family_notes', 'firmclientfamily\FirmclientfamilyDashboardController@add_family_notes')->name('clientfamily.add_family_notes');
    Route::get('clientfamilydashboard/familyInvoice', 'firmclientfamily\FirmclientfamilyDashboardController@Invoice')->name('clientfamily.familyInvoice');

	Route::get('clientfamilydashboard/getCaseFamilyDataDocument/{id}', 'firmclientfamily\FirmclientfamilyDashboardController@getCaseFamilyDataDocument')->name('clientfamily.getCaseFamilyDataDocument');