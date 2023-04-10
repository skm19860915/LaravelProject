<?php
Route::get('pdfform', 'admin\PDFFormController@index')->name('pdfform');
Route::get('/pdfform/viewpdf/{id}', 'admin\PDFFormController@viewpdf')->name('viewpdf');
Route::get('/pdfform/setupfields/{id}', 'admin\PDFFormController@setupfields')->name('pdfform.setupfields');
Route::get('/pdfform/pdftest/{id}', 'admin\PDFFormController@pdftest')->name('pdfform.pdftest');
Route::get('/pdfform/pdftestnew/{id}', 'admin\PDFFormController@pdftestnew')->name('pdfform.pdftestnew');

/* --------------AJAX----------- */
Route::get('/pdfform/FieldMetaEntry/{id}', 'admin\PDFFormController@FieldMetaEntry')->name('pdfform.FieldMetaEntry');
Route::get('/pdfform/PDFList/{id}', 'admin\PDFFormController@PDFList')->name('pdfform.PDFList');
Route::get('/pdfform/parentgroups/{id}', 'admin\PDFFormController@parentgroups')->name('pdfform.parentgroups');
Route::get('/pdfform/updateparentgroups/{id}', 'admin\PDFFormController@updateparentgroups')->name('pdfform.updateparentgroups');

Route::get('/pdfform/AllPdfFiles', 'admin\PDFFormController@AllPdfFiles')->name('pdfform.AllPdfFiles');
Route::get('/pdfform/SaveAGroup', 'admin\PDFFormController@SaveAGroup')->name('pdfform.SaveAGroup');
Route::get('/pdfform/FieldNameUpdate', 'admin\PDFFormController@FieldNameUpdate')->name('pdfform.FieldNameUpdate');
Route::get('/pdfform/DeleteGroup/{id}', 'admin\PDFFormController@DeleteGroup')->name('pdfform.DeleteGroup');
Route::get('/pdfform/IsmasterField/{id}', 'admin\PDFFormController@IsmasterField')->name('pdfform.IsmasterField');
Route::get('/pdfform/GetAllFieldByFileName/{id}', 'admin\PDFFormController@GetAllFieldByFileName')->name('pdfform.GetAllFieldByFileName');

Route::get('/pdfform/autoCompletedata/', 'admin\PDFFormController@autoCompletedata')->name('pdfform.autoCompletedata');


Route::get('/pdfform/PDFMasterCreate/', 'admin\PDFFormController@PDFMasterCreate')->name('pdfform.PDFMasterCreate');

