<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\Dropbox;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\FirmSetting;
use App\Models\CalendarSetting;
use App\Models\UserMeta;
use App\Models\Firm;
use App;
use DB;
use QuickBooksOnline\API\DataService\DataService;

class FirmSettingController extends Controller {

    public function __construct() {
        require_once(base_path('public/QuickBook/v2/vendor/autoload.php'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function smsindex() {
        return view('firmadmin.setting.smsindex');
    }

    public function Apps() {
        $data = Auth::User();
        $id = $data->id;
        

        $QuickBookUrl = $this->QuickbookToken($data->id, $data->QBcompanyID, $data->QBToken, $data->QBTokenDate, $data->QBConnect);
        // $QuickBookUrl = $authUrl;
        return view('firmadmin.setting.app', compact('QuickBookUrl', 'id'));
    }

    public function autoQuickBookToken() {
        $data = Auth::User();
        $conf = require_once(base_path('public/QuickBook/v2/config.php'));
        $dataService = DataService::Configure(array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => $conf['client_id'],
                    'ClientSecret' => $conf['client_secret'],
                    'RedirectURI' => $conf['oauth_redirect_uri'],
                    'baseUrl' => "https://quickbooks.api.intuit.com",
                    'refreshTokenKey' => $data->QBToken,
                    'QBORealmID' => $data->QBcompanyID,
        ));
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
        $dataService->updateOAuth2Token($refreshedAccessTokenObj);
        $data->QBToken; 
        $token = $refreshedAccessTokenObj->getRefreshToken();
        $tokenD = strtotime($refreshedAccessTokenObj->getRefreshTokenExpiresAt());
        $d=$refreshedAccessTokenObj->getRefreshTokenExpiresAt();
        
        DB::update('update users set QBToken=?,QBTokenDate=?,QBTokenNewDate=? where id = ?', [$token, $tokenD,$d,  $data->id]);
        echo 'TokenUpdate'.$token;
    }

    public function QbookConnect() {
        $data = Auth::User();
        $config = require_once(base_path('public/QuickBook/v2/config.php'));
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' =>  $config['client_secret'],
            'RedirectURI' => $config['oauth_redirect_uri'],
            'scope' => $config['oauth_scope'],
            'baseUrl' => "https://quickbooks.api.intuit.com"
        ));

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $parseUrl = $this->parseAuthRedirectUrl($_SERVER['QUERY_STRING']);

        /*
         * Update the OAuth2Token
         */
        $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($parseUrl['code'], $parseUrl['realmId']);
        $dataService->updateOAuth2Token($accessToken);
        $token = $accessToken->getRefreshToken();
        $tokenD = strtotime($accessToken->getRefreshTokenExpiresAt());

        /*
         * Setting the accessToken for session variable
         */
        // $_SESSION['sessionAccessToken'] = $accessToken;
        $j = array();
        $j['getclientID'] = $accessToken->getclientID();
        $j['getClientSecret'] = $accessToken->getClientSecret();
        $j['getRefreshToken'] = $accessToken->getRefreshToken();
        $j['getRealmID'] = $accessToken->getRealmID();
        $j['getBaseURL'] = $accessToken->getBaseURL();
        $token1 = json_encode($j);
        DB::update('update users set QBToken=?,QBTokenDate=?,QBConnect = ? where id = ?', [$token1, $tokenD, 1, $data->id]);
        return redirect('firm/setting/app')->with('success', 'QuickBook Connected Successfully!');
    }

    public function parseAuthRedirectUrl($url)
    {
        parse_str($url,$qsArray);
        return array(
            'code' => $qsArray['code'],
            'realmId' => $qsArray['realmId']
        );
    }
    /**
     * Connect To QuickBook  Refrence.
     *
     * @return QuickBooksOnline\API\DataService\DataService
     */
    function QuickbookToken($UID, $QBCID, $QBToken, $QBTokenDate, $QBConnect = 0) {
        $data['UID'] = $UID;
        $data['QBCID'] = $QBCID;
        $data['QBToken'] = $QBToken;
        $data['QBTokenDate'] = $QBTokenDate;
        $data['QBConnect'] = $QBConnect;

        $conf = require_once(base_path('public/QuickBook/v2/config.php'));
        $dataService = DataService::Configure(array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => $conf['client_id'],
                    'ClientSecret' => $conf['client_secret'],
                    'RedirectURI' => $conf['oauth_redirect_uri'],
                    'scope' => $conf['oauth_scope'], //"com.intuit.quickbooks.accounting",
                    'baseUrl' => "https://quickbooks.api.intuit.com"
        ));
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $authorizationCodeUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();

        if (isset($_REQUEST['code'])) {

            #print_r($_REQUEST,$_REQUEST['code'], $_REQUEST["realmId"]);
            $accessTokenObj = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($_REQUEST['code'], $_REQUEST["realmId"]);
            #print_r($OAuth2LoginHelper);
            $accessTokenValue = $accessTokenObj->getAccessToken();
            $refreshTokenValue = $accessTokenObj->getRefreshToken();
            $dataService = DataService::Configure(array(
                        'auth_mode' => 'oauth2',
                        'ClientID' => $conf['client_id'],
                        'ClientSecret' => $conf['client_secret'],
                        'RedirectURI' => $conf['oauth_redirect_uri'],
                        'baseUrl' => "https://quickbooks.api.intuit.com",
                        'refreshTokenKey' => $refreshTokenValue,
                        'QBORealmID' => $conf['QBORealmID'],
            ));
            /*
             * Update the OAuth2Token of the dataService object
             */
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
            $dataService->updateOAuth2Token($refreshedAccessTokenObj);
            echo '<pre>';
            print_r($refreshedAccessTokenObj);
            print_r($refreshedAccessTokenObj->getRefreshToken());
            die;
        } else {
            $data['TokenUrl'] = $authorizationCodeUrl;
            return ($data);
        }
    }

    public function smsgetData() {
        $data = Auth::User();
        $message = FirmSetting::where('category', "SMS")->where('firm_id', $data->firm_id)->get();
        foreach ($message as $key => $value) {
            $message[$key]->stat = ($value->status == 1) ? "Active" : "Inactive";
        }
        return datatables()->of($message)->toJson();
    }

    public function emailindex() {
        return view('firmadmin.setting.emailindex');
    }

    public function emailgetData() {
        $data = Auth::User();
        $message = FirmSetting::where('category', "EMAIL")->where('firm_id', $data->firm_id)->get();

        foreach ($message as $key => $value) {

            $message[$key]->stat = ($value->status == 1) ? "Active" : "Inactive";
        }

        return datatables()->of($message)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function messageUpdate($id) {

        $record = FirmSetting::where('id', $id)->first();
        return view('firmadmin.setting.edit', compact('record'));
    }

    public function update_message(Request $request) {
        $firm_id = Auth::User()->firm_id;
        $data = [
            'message' => $request->message,
            'status' => $request->status,
        ];

        FirmSetting::where('id', $request->setting_id)->update($data);

        if ($request->category == "SMS") {
            return redirect('firm/setting/sms')->with('success', 'SMS Message update successfully');
        } else {
            return redirect('firm/setting/email')->with('success', 'EMAIL Message update successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id) {
        FirmSetting::where('id', $id)->delete();
        return redirect('firm/setting')->with('success', 'Message deleted successfully!');
    }

    public function calendar_setting(Request $request) {
        $id = Auth::User()->id;
        foreach ($request->setting as $k => $v) {
            $res = CalendarSetting::where('user_id', $id)->where('key', $k)->count();
            if ($res) {
                $data = [
                    'value' => $v
                ];
                CalendarSetting::where('user_id', $id)->where('key', $k)->update($data);
            } else {
                $data = [
                    'user_id' => $id,
                    'key' => $k,
                    'value' => $v
                ];
                CalendarSetting::create($data);
            }
        }
        $result = array();
        $result['status'] = true;
        echo json_encode($result);
    }

    public function app_setting() {
        $data = Auth::User();
        $id = $data->id;
        // $usermeta = get_user_meta($id);
        // pre($usermeta);
        // die();
        return view('firmadmin.setting.app_setting', compact('id'));
    }

    public function theme_setting() {
        $data = Auth::User();
        $id = $data->id;
        $firm = Firm::where('id', $data->firm_id)->first();
        return view('firmadmin.setting.theme_setting', compact('id', 'firm'));
    }

    public function update_app_setting(Request $request) {
        $data = Auth::User();
        $id = $data->id;
        foreach ($request->usermeta as $key => $meta) {
            update_user_meta($id, $key, $meta);
        }
        return redirect('firm/setting/app')->with('success', 'Successfully saved integration key');
    }

    public function update_theme_setting(Request $request) {
        $data = Auth::User();
        $id = $data->id;
        $firm = Firm::select('firms.*', 'users.id as uid')
                ->where('firms.id', $data->firm_id)
                ->join('users', 'users.email', '=', 'firms.email')
                ->first();
        if(!empty($request->theme_logo))
        {
            $theme_logo = Storage::put('client_doc', $request->theme_logo);
            update_user_meta($firm->uid, 'theme_logo', $theme_logo);
        }
        update_user_meta($firm->uid, 'theme_color', $request->theme_color);
        return redirect('firm/setting/theme_setting')->with('success', 'Theme setting update successfully');
    }
}
