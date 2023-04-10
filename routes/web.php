<?php
setTImezone();
Route::get('/', function() {
    return redirect(route('admin.dashboard'));           
});


Route::get('home', function() {

    /*pre(Session::all());
    die();*/
    
    if (Session::get('role_id') == '2'){
      return redirect(route('admin.userdashboard'));  
    }
    if (Session::get('role_id') == '3'){
      return redirect(route('admin.supportdashboard'));
    }else{
        return redirect(route('admin.dashboard'));
    }
    
});



Route::group(['middleware' => ['isadmin','isactive']], function () {
    Route::name('admin.')->prefix('admin')->middleware('auth')->group(function() {

        Route::group(['middleware' => 'islavel1'], function () {
            include_route_files(__DIR__.'/Admin/');
        });

        Route::group(['middleware' => 'islavel2'], function () {
            include_route_files(__DIR__.'/Adminuser/');
        });

        Route::group(['middleware' => 'islavel3'], function () {
            include_route_files(__DIR__.'/Adminsupport/');
        });
        
    });
});



Route::group(['middleware' => 'isfirm'], function () {

    Route::name('firm.')->prefix('firm')->middleware('auth')->group(function() {
        
        Route::group(['middleware' => 'islavel1'], function () {
            
            Route::get('first_reset_admin_password', 'firmadmin\FirmDashboardController@first_reset_password')->name('first_reset_admin_password');

            Route::post('update_first_admin_password', 'firmadmin\FirmDashboardController@update_first_password')->name('update_first_admin_password');

            Route::group(['middleware' => 'resetpassword'], function () {

                Route::get('admindashboard', 'firmadmin\FirmDashboardController@index')->name('admindashboard');
                Route::get('payment_method1', 'firmadmin\FirmDashboardController@payment_method1')->name('firm.payment_method1');
                Route::get('create_charge1', 'firmadmin\FirmDashboardController@create_charge1')->name('firm.create_charge1');
                
                Route::get('caseIsConform/{id}', 'firmadmin\FirmDashboardController@caseIsConform')->name('firm.caseIsConform');
                
                Route::group(['middleware' => 'isactive'], function () {
                    include_route_files(__DIR__.'/Firmadmin/');
                });
            });

        });

        Route::group(['middleware' => ['islavel2','isactive']], function () {

            Route::get('first_reset_user_password', 'firmuser\FirmuserDashboardController@first_reset_password')->name('first_reset_user_password');

            Route::post('update_first_user_password', 'firmuser\FirmuserDashboardController@update_first_password')->name('update_first_user_password');

            Route::group(['middleware' => 'resetpassword'], function () {

                include_route_files(__DIR__.'/Firmuser/'); 

            });
        });

        Route::group(['middleware' => ['islavel3','isactive']], function () {

            Route::get('first_reset_client_password', 'firmclient\FirmclientDashboardController@first_reset_password')->name('first_reset_client_password');

            Route::post('update_first_client_password', 'firmclient\FirmclientDashboardController@update_first_password')->name('update_first_client_password');

            Route::group(['middleware' => 'resetpassword'], function () {
                include_route_files(__DIR__.'/Firmclient/');
            });
        });
        Route::group(['middleware' => ['islavel4','isactive']], function () {

            Route::get('first_reset_client_password', 'firmclient\FirmclientDashboardController@first_reset_password')->name('first_reset_client_password');

            Route::post('update_first_client_password', 'firmclient\FirmclientDashboardController@update_first_password')->name('update_first_client_password');

            Route::group(['middleware' => 'resetpassword'], function () {
                include_route_files(__DIR__.'/Firmclientfamily/');
            });
        }); 

    });
});



Route::middleware('auth')->get('logout', function() {
    Auth::logout();
    if(session()->has('info'))
    {
        return redirect(route('login'))->withInfo(session()->pull('info'));
    }
    return redirect(route('login'))->withInfo('You have successfully logged out!');
})->name('logout');

Auth::routes(['verify' => true]);

Route::name('js.')->group(function() {
    Route::get('dynamic.js', 'JsController@dynamic')->name('dynamic');
});

// Get authenticated user
Route::get('users/auth', function() {
    return response()->json(['user' => Auth::check() ? Auth::user() : false]);
});


Route::get('/messages', 'MessageController@index')->name('messages.index');
Route::get('/messages/NotificationCenter', 'MessageController@NotificationCenter')->name('messages.NotificationCenter');
Route::get('/messages/allread/{id}/{ids}', 'MessageController@allread')->name('messages.allread');
Route::get('/messages/chatUsers', 'MessageController@chatUsers')->name('messages.chatUsers');
Route::any('/messages/chatNote/{id}', 'MessageController@chatNote')->name('messages.chatNote');
Route::any('/messages/readnotification/{id}', 'MessageController@readnotification')->name('messages.readnotification');
Route::get('/messages/ReadNotify/{id}', 'MessageController@ReadNotify')->name('messages.ReadNotify');
Route::any('/messages/ChatInPopup/{id}', 'MessageController@ChatInPopup')->name('messages.ChatInPopup');
Route::any('/messages/view_unread_notification', 'MessageController@view_unread_notification')->name('messages.view_unread_notification');
Route::any('/messages/view_all_notification', 'MessageController@view_all_notification')->name('messages.view_all_notification');



Route::get('/messages/chat/{ids}', 'MessageController@chat')->name('messages.chat');
Route::get('/getCountries','HomeController@getCounties');
Route::get('/getStates/{id}','HomeController@getStates');
Route::get('/getCities/{id}','HomeController@getCities');
Route::get('/profile','HomeController@profile');
Route::post('/updateprofile','HomeController@updateprofile');
Route::get('/editpdf/{id}','HomeController@editpdf');
Route::get('/delete_card/{id}','HomeController@delete_card');
Route::get('/edit_questionnaire/{formtype}/{lang}/{id}','HomeController@edit_questionnaire');
Route::post('/update_questionnaire','HomeController@update_questionnaire');
Route::post('/add_questionnaire_fn','HomeController@add_questionnaire_fn');
Route::post('/send_invoice','HomeController@send_invoice');
Route::get('/viewinvoice/{id}','HomeController@viewinvoice');
Route::post('/pay_for_invoice','HomeController@pay_for_invoice');
Route::post('/sendinvoice/{id}','HomeController@sendinvoice');
Route::get('/printinvoice/{id}','HomeController@printinvoice');
Route::get('/delete_event/{id}','HomeController@delete_event');
Route::post('/UpdateFamily','HomeController@UpdateFamily');
Route::get('/NewQbookConnect','HomeController@NewQbookConnect');
Route::get('/FindShortCodeData/{id}/{shortcode}/{return}/{CID}', 'HomeController@FindShortCodeData')->name('pdfform.FindShortCodeData');
Route::post('/FindShortCodeDataSave', 'HomeController@FindShortCodeDataSave')->name('pdfform.FindShortCodeDataSave');
Route::any('/AjaxData/{action}', 'HomeController@AjaxData')->name('pdfform.AjaxData');
Route::any('/FileUpload/', 'HomeController@FileUpload')->name('pdfform.FileUpload');
Route::get('/logout_google','HomeController@logout_google');
Route::post('/setAdditionalDocument', 'HomeController@setAdditionalDocument');
Route::post('/addcasenotes','HomeController@addcasenotes');
Route::post('/sendtextmsg','HomeController@sendtextmsg');
Route::any('/search','HomeController@search');
// Outlook 

Route::get('/outlook/signin', 'outlook\AuthController@signin');
Route::get('/outlook/authorize', 'outlook\AuthController@gettoken');
Route::get('/outlook/mail', 'outlook\OutlookController@mail')->name('mail');
Route::get('/outlook/calendar', 'outlook\OutlookController@calendar')->name('calendar');
Route::get('/outlook/contacts', 'outlook\OutlookController@contacts')->name('contacts');



/*-------------Cronjob-------------*/

Route::get('/cron', 'CronjobController@index')->name('CronjobController.index');
Route::get('/cron/PdfMetaEnter', 'CronjobController@PdfMetaEnter')->name('CronjobController.PdfMetaEnter');
Route::get('/cron/TestPhpCode', 'CronjobController@TestPhpCode')->name('CronjobController.TestPhpCode');

Route::get('/cron/UserMonthlyPayment', 'CronjobController@UserMonthlyPayment')->name('CronjobController.UserMonthlyPayment');
Route::any('/cron/DirectUseHelper/{action}', 'CronjobController@DirectUseHelper')->name('CronjobController.DirectUseHelper');