<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

        Route::get('QbookConnect', 'admin\FirmSettingController@QbookConnect')->name('QbookConnect');
        Route::get('mailtest', 'admin\mailTestController@test')->name('mailtest'); 

?> 