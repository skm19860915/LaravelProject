<?php
	Route::get('userdashboard', 'adminuser\UserDashboardController@index')->name('userdashboard');
	Route::get('leave_application', 'adminuser\UserDashboardController@leave_application')->name('leave_application');
	Route::post('send_leave_application', 'adminuser\UserDashboardController@send_leave_application')->name('send_leave_application');
	Route::get('usercalendar', 'adminuser\UserDashboardController@usercalendar')->name('usercalendar');
	Route::post('createuserevent', 'adminuser\UserDashboardController@createuserevent')->name('createuserevent');
	Route::get('new_assignments', 'adminuser\UserDashboardController@new_assignments')->name('new_assignments');
	Route::get('getNewAssignments', 'adminuser\UserDashboardController@getNewAssignments')->name('getNewAssignments');
	Route::get('accept_assignment/{id}', 'adminuser\UserDashboardController@accept_assignment')->name('accept_assignment');
	Route::get('denied_assignment/{id}', 'adminuser\UserDashboardController@denied_assignment')->name('denied_assignment');