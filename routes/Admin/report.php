<?php

Route::get('report/firmDetails', 'admin\ReportController@firmDetails_report')->name('report.firmDetails');
Route::get('report/firmDetails_getData', 'admin\ReportController@firmDetails_getData')->name('report.firmDetails_getData');

Route::get('report/firmUse', 'admin\ReportController@firmUse_report')->name('report.firmUse');
Route::post('report/firmUse_getData', 'admin\ReportController@firmUse_getData')->name('report.firmUse_getData');

Route::get('report/financialFirm', 'admin\ReportController@financialFirm_report')->name('report.financialFirm');
Route::get('report/financialFirm_getData', 'admin\ReportController@financialFirm_getData')->name('report.financialFirm_getData');



Route::get('report/FirmCaseReport', 'admin\ReportController@FirmCase_report')->name('report.FirmCaseReport');
Route::get('report/FirmCaseGetDate', 'admin\ReportController@FirmCase_getDate')->name('report.FirmCaseGetDate');


Route::get('report/VaCaseReport', 'admin\ReportController@VaCase_report')->name('report.VaCaseReport');
Route::get('report/VaCaseGetDate', 'admin\ReportController@VaCase_getDate')->name('report.VaCaseGetDate');


Route::get('report/FinancialRP', 'admin\ReportController@Financial')->name('report.FinancialRP');
Route::post('report/financialgetdate', 'admin\ReportController@financialgetdate')->name('report.financialgetdate');

