<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\{
    UserUpdateRequest,
    UserAddRequest
};
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\TilaEmailTemplate;
use App\Models\CaseType;
use App\User;
use App;
use DB;
use QuickBooksOnline\API\DataService\DataService;

class SettingController extends Controller {

    public function __construct() {
        require_once(base_path('public/QuickBook/gettoken.php'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function emailindex() {
        return view('admin.setting.emailindex');
    }

    public function emailgetData() {

        $message = TilaEmailTemplate::get();

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

        $record = TilaEmailTemplate::where('id', $id)->first();
        return view('admin.setting.edit', compact('record'));
    }

    public function update_message(Request $request) {
        $data = [
            'massage' => $request->message,
            'is_undo' => 1,
            'status' => 1,
        ];

        TilaEmailTemplate::where('id', $request->setting_id)->update($data);

        return redirect('admin/setting/email')->with('success', 'EMAIL Message update successfully');
    }

    function undo_message($id) {
        $record = TilaEmailTemplate::where('id', $id)->first();
        $data = [
            'massage' => $record->standard_massage,
            'is_undo' => 0
        ];
        TilaEmailTemplate::where('id', $record->id)->update($data);
        return redirect('admin/setting/email')->with('success', 'EMAIL Message undo successfully');
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

    public function appsetting() {
        $data = Auth::User();
        $id = 1;
        $QuickBookUrl = $this->QuickbookToken($data->id, $data->QBcompanyID, $data->QBToken, $data->QBTokenDate, $data->QBConnect);
        return view('admin.setting.app', compact('QuickBookUrl', 'id'));
    }

    public function app_setting() {
        $id = 1;
        return view('admin.setting.app_setting', compact('id'));
    }

    public function app_setting_update(Request $request) {
        $id = 1;
        foreach ($request->usermeta as $key => $meta) {
            update_user_meta($id, $key, $meta);
        }
        return redirect('admin/setting/appsetting')->with('success', 'Successfully saved integration key');
    }

    public function autoQuickBookToken() {
        $data = Auth::User();
        $conf = require_once(base_path('public/QuickBook/conf.php'));
        $conf['oauth_redirect_uri'] = 'https://tila.app.stoute.co/admin/setting/QbookConnect';

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
        $d = $refreshedAccessTokenObj->getRefreshTokenExpiresAt();

        DB::update('update users set QBToken=?,QBTokenDate=?,QBTokenNewDate=? where id = ?', [$token, $tokenD, $d, $data->id]);
        echo 'TokenUpdate' . $token;
    }

    public function QbookConnect() {
        $data = Auth::User();
        $conf = require_once(base_path('public/QuickBook/conf.php'));
        $confR = 'https://tila.app.stoute.co/admin/setting/QbookConnect';
        
        $dataService = DataService::Configure(array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => $conf['client_id'],
                    'ClientSecret' => $conf['client_secret'],
                    'RedirectURI' => $confR,
                    'scope' => $conf['oauth_scope'],
                    'baseUrl' => "https://quickbooks.api.intuit.com"
        ));
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $authorizationCodeUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();

        if (isset($_REQUEST['code'])) {

            #print_r($_REQUEST,$_REQUEST['code'], $_REQUEST["realmId"]);
            $accessTokenObj = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($_REQUEST['code'], $_REQUEST["realmId"]);
            #print_r($OAuth2LoginHelper);
            $accessTokenValue = $accessTokenObj->getAccessToken();
            echo $refreshTokenValue = $accessTokenObj->getRefreshToken();
            
            $dataService = DataService::Configure(array(
                        'auth_mode' => 'oauth2',
                        'ClientID' => $conf['client_id'],
                        'ClientSecret' => $conf['client_secret'],
                        'RedirectURI' => $confR,
                        'baseUrl' => "https://quickbooks.api.intuit.com",
                        'refreshTokenKey' => $refreshTokenValue,
                        'QBORealmID' => $data->QBcompanyID,
            ));
            /*
             * Update the OAuth2Token of the dataService object
             */
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
            $dataService->updateOAuth2Token($refreshedAccessTokenObj);

            $token = $refreshedAccessTokenObj->getRefreshToken();
            $tokenD = strtotime($refreshedAccessTokenObj->getRefreshTokenExpiresAt());

            DB::update('update users set QBToken=?,QBTokenDate=?,QBConnect = ? where id = ?', [$token, $tokenD, 1, $data->id]);
            return redirect('admin/setting/appsetting')->with('success', 'QuickBook Connected Successfully!');
        }
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

        $conf = require_once(base_path('public/QuickBook/conf.php'));
        $conf['oauth_redirect_uri'] = 'https://tila.app.stoute.co/admin/setting/QbookConnect';

        $dataService = DataService::Configure(array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => $conf['client_id'],
                    'ClientSecret' => $conf['client_secret'],
                    'RedirectURI' => $conf['oauth_redirect_uri'],
                    'scope' => $conf['oauth_scope'],
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

    public function casetypes() {
        $id = 1;
        $CaseTypes = CaseType::select('*')->get();
        return view('admin.setting.casetypes', compact('id', 'CaseTypes'));
    }
    public function update_case_cost(Request $request) {
        $res = array();
        $validator = Validator::make($request->all(), [
                    'cost' => 'required|string'
        ]);
        if ($validator->fails()) {
            $res['status'] = false;
            $res['msg'] = $validator->errors()->first();
            echo json_encode($res);
            die();
        }
        CaseType::where('id', $request->id)->update(['VP_Pricing' => $request->cost]);
        $res['status'] = true;
        $res['msg'] = 'Case cost update successfully';
        echo json_encode($res);
        die();
        // $CaseTypes = CaseType::select('*')->get();
    }
}
