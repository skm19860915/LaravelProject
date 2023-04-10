<?php
	Route::get('report/ReportingJson', 'firmadmin\FirmReportController@ReportingJson')->name('report.ReportingJson');
        
        
        Route::get('report/deadline_report', 'firmadmin\FirmReportController@deadline_report')->name('report.deadline_report');
	Route::get('report/deadline_getData', 'firmadmin\FirmReportController@deadline_getData')->name('report.deadline_getData');


	Route::get('report/expirationDates_report', 'firmadmin\FirmReportController@expirationDates_report')->name('report.expirationDates_report');
	Route::get('report/expirationDates_getData', 'firmadmin\FirmReportController@expirationDates_getData')->name('report.expirationDates_getData');


	Route::get('report/openedCases_report', 'firmadmin\FirmReportController@openedCases_report')->name('report.openedCases_report');
	Route::get('report/openedCases_getData', 'firmadmin\FirmReportController@openedCases_getData')->name('report.openedCases_getData');


	Route::get('report/closedCases_report', 'firmadmin\FirmReportController@closedCases_report')->name('report.closedCases_report');
	Route::get('report/closedCases_getData', 'firmadmin\FirmReportController@closedCases_getData')->name('report.closedCases_getData');


	Route::get('report/courtDates_report', 'firmadmin\FirmReportController@courtDates_report')->name('report.courtDates_report');
	Route::get('report/courtDates_getData', 'firmadmin\FirmReportController@courtDates_getData')->name('report.courtDates_getData');


	Route::get('report/nationality_report', 'firmadmin\FirmReportController@nationality_report')->name('report.nationality_report');
	Route::post('report/nationality_getData', 'firmadmin\FirmReportController@nationality_getData')->name('report.nationality_getData');


	Route::get('report/submittedCases_report', 'firmadmin\FirmReportController@submittedCases_report')->name('report.submittedCases_report');
	Route::get('report/submittedCases_getData', 'firmadmin\FirmReportController@submittedCases_getData')->name('report.submittedCases_getData');


	Route::get('report/nextStageCase_report', 'firmadmin\FirmReportController@nextStageCase_report')->name('report.nextStageCase_report');
	Route::get('report/nextStageCase_getData', 'firmadmin\FirmReportController@nextStageCase_getData')->name('report.nextStageCase_getData');


	Route::get('report/incompleteCases_report', 'firmadmin\FirmReportController@incompleteCases_report')->name('report.incompleteCases_report');
	Route::get('report/incompleteCases_getData', 'firmadmin\FirmReportController@incompleteCases_getData')->name('report.incompleteCases_getData');


	Route::get('report/leads_report', 'firmadmin\FirmReportController@leads_report')->name('report.leads_report');
	Route::post('report/leads_getData', 'firmadmin\FirmReportController@leads_getData')->name('report.leads_getData');


	