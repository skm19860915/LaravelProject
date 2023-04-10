<?php
Route::get('calendar', 'firmadmin\FirmCalendarController@index')->name('calendar');
Route::get('calendar_setting', 'firmadmin\FirmCalendarController@calendar_setting')->name('calendar_setting');
Route::post('calendar/create_firm_event', 'firmadmin\FirmCalendarController@create_firm_event')->name('calendar.create_firm_event');