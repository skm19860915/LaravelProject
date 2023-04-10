<?php
	Route::get('calendar', 'admin\CalendarController@index')->name('calendar');
	Route::get('calendarsetting', 'admin\CalendarController@calendarsetting')->name('calendar.calendarsetting');
	Route::post('calendar/update_calendarsetting', 'admin\CalendarController@update_calendarsetting')->name('calendar.update_calendarsetting');
	Route::post('calendar/create_admin_event', 'admin\CalendarController@create_admin_event')->name('calendar.create_admin_event');