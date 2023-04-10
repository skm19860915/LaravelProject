<?php
Route::get('usercase', 'firmuser\FirmuserCaseController@usercase')->name('usercase');
Route::get('usercase/mycase', 'firmuser\FirmuserCaseController@mycase')->name('usercase.mycase');
Route::get('usercase/allcase', 'firmuser\FirmuserCaseController@allcase')->name('usercase.allcase');
Route::get('usercase/getData', 'firmuser\FirmuserCaseController@getData')->name('usercase.getData');
Route::get('usercase/show/{id}', 'firmuser\FirmuserCaseController@show')->name('usercase.show');