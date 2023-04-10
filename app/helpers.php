<?php

if (!function_exists('include_route_files')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function include_route_files($folder) {
        try {
            $rdi = new recursiveDirectoryIterator($folder);
            $it = new recursiveIteratorIterator($rdi);

            while ($it->valid()) {
                if (!$it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }

                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}


if (!function_exists('otp_genrater')) {

    /**
     * this code genrate rendom number to given digits.
     *
     * @param $digits
     */
    function otp_genrater($digits) {
        $digits = ($digits != 0) ? $digits : 4;
        return str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
    }

}










if (!function_exists('Eadmin')) {

    function Eadmin() {
        // return 'testersnv@gmail.com';
        return 'snvservices.ravikant@gmail.com';
    }

}
if (!function_exists('COPYMAIL')) {

    function COPYMAIL() {
        return 'testersnv@gmail.com';
    }

}
if (!function_exists('QB_refreshToken')) {

    function QB_refreshToken($user) {
        // use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
        // $accessToken = json_decode($user->QBToken);
        // $oauth2LoginHelper = new OAuth2LoginHelper($accessToken->getclientID,$accessToken->getClientSecret);
        // $newAccessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($accessToken->getRefreshToken);
        // $newAccessTokenObj->setRealmID($accessToken->getRealmID);
        // $newAccessTokenObj->setBaseURL($accessToken->getBaseURL);
        // // $_SESSION['sessionAccessToken']=$newAccessTokenObj;
        // pre($accessToken);
        // pre($newAccessTokenObj);
        // die();
    }

}

function getUserIDByClientID($id) {
    $uc = DB::table("new_client")->where("id", $id)->first();
    return $uc->user_id;
}

if (!function_exists('CurlDataStatue')) {

    function CurlDataStatue($url, $status = null, $wait = 3) {
        $exists = '';

        $file = base_path('storage/app/' . $url);
        if (is_file($file)) {
            return true;
        } else {
            return FALSE;
        }
    }

}


include "Helpers/comon.php";
include "Helpers/CuromPDF.php";
include "Helpers/ChatHelper.php";
include "Helpers/Calender.php";
include "Helpers/customhelper.php";
include "Helpers/AllAuthentication.php";
include "Helpers/PdfRemoteWeb.php";












