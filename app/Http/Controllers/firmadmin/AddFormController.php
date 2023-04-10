<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\User;
use App\Models\FirmCase;
use App\Models\ClientInformation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\AdminTask;
use App;
use App\Dropbox;
use Purl\Url;
use DB;
use App\Notifications\DatabaseNotification;
use Notification;
use App\Models\Firm;
use App\Models\Newclient;

class AddFormController extends Controller
{
    private $api_client;
    private $content_client;
    public function __construct(Dropbox $dropbox) {
        require_once(base_path('public/QuickBook/gettoken.php'));
        require_once(base_path('public/calenderApi/settings.php'));
        $this->api_client = $dropbox->api();
        $this->content_client = $dropbox->content();
    }
    public function index($id) {
        $currunt_user = Auth::User();
        $client_information_forms = ClientInformation::select('client_information_forms.*', 'client_information_forms.id as info_id', 'client_information_forms.status as status1','new_client.*','case.VP_Assistance','case.status as case_status')
            ->join('new_client', 'new_client.id', '=', 'client_information_forms.client_id')
            ->where('client_information_forms.client_id',$id)
            ->join('case', 'client_information_forms.case_id', '=', 'case.id')
            ->get();
            foreach ($client_information_forms as $k => $v) {
                $residence_address = json_decode($v->residence_address);
                $client_information_forms[$k]->residence_address = $residence_address;
                if(!empty($residence_address->country) && $residence_address->country) {
                    $client_information_forms[$k]->country = getCountryName($residence_address->country);
                }
                if(!empty($residence_address->state) && $residence_address->state) {
                    $client_information_forms[$k]->state = getStateName($residence_address->state);
                }
                if(!empty($residence_address->city) && $residence_address->city) {
                    $client_information_forms[$k]->city = getCityName($residence_address->city);
                }
                if(!empty($residence_address->address) && $residence_address->address) {
                    $client_information_forms[$k]->address = $residence_address->address;
                }
            }
            // pre($client_information_forms);
            // die();
            $client = Newclient::where('id', $id)->first();
    	return view('firmadmin.forms.index', compact('client_information_forms', 'client'));
    }
    public function addform(Request $request, $id) {
        /*
        if(!$request->session()->get('DBox_token')) {

            if($request->has('code')) {
                $data = [
                'code' => $request->input('code'),
                'grant_type' => 'authorization_code',
                'client_id' => env('DROPBOX_APP_KEY'),
                'client_secret' => env('DROPBOX_APP_SECRET'),
                'redirect_uri' => env('DROPBOX_REDIRECT_URI')
            ];

            $response = $this->api_client->request(
                'POST',
                '/1/oauth2/token',
                ['form_params' => $data]
            );

            $response_body = json_decode($response->getBody(), true);
            $access_token = $response_body['access_token'];
            $request->session()->put('DBox_token', $access_token);
            
            return redirect('firm/forms/addform')->with('success','Connect with Dropbox successfully!');
            }
            else {
                $url = new Url('https://www.dropbox.com/1/oauth2/authorize');
                $url->query->setData([
                    'response_type' => 'code',
                    'client_id' => env('DROPBOX_APP_KEY'),
                    'redirect_uri' => env('DROPBOX_REDIRECT_URI')
                ]);
                return redirect($url->getUrl());
            }
        }

        if (false) {
            $query = 'i-9supinstr';

            $data = json_encode(
                [
                    'query' => $query
                ]
            );

            $response = $this->api_client->request(
                'POST', '/2/files/search_v2',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $request->session()->get('DBox_token'),
                        'Content-Type' => 'application/json'
                    ],
                    'body' => $data
            ]);

            $search_results = json_decode($response->getBody(), true);
            $matches = $search_results['matches'];
        }
        */
    	$data = Auth::User();
        $clients = User::select('users.*', 'new_client.*')
        ->join('new_client', 'users.id', '=', 'new_client.user_id')
        ->where('users.firm_id' ,'=', $data->firm_id)
        ->get();
        $client = Newclient::where('id', $id)->first();
    	return view('firmadmin.forms.addform', compact('clients', 'data', 'client'));
    }
    public function regenerate_token(Request $request) {
           $response = $this->api_client->request(
                'POST', '/auth/token/revoke',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $request->session()->get('DBox_token'),
                        'Content-Type' => 'application/json'
                    ] 
            ]);
            $data = [
                //'code' => $request->input('code'),
                'grant_type' => 'refresh_token',
                'client_id' => env('DROPBOX_APP_KEY'),
                'client_secret' => env('DROPBOX_APP_SECRET'),
                'refresh_token' => $request->session()->get('DBox_token')
            ];

            $response_body = json_decode($response->getBody(), true);
            $access_token = $response_body['access_token'];
            pre($response_body);
            die();
    }

    public function client_Cases($id)
    {
    	$states = FirmCase::select("*")
                    ->where("client_id",$id)
                    ->pluck("id","id");
        return response()->json($states);
    }

    public function create_form(Request $request) {
        $file_data = json_decode($request->file_data);
        $path = $file_data->id;
        $client = new \Spatie\Dropbox\Client(env('DROPBOX_TOKEN'));
        $a = $client->download($path);
        $file = 'forms/'.$file_data->name;
        Storage::put($file, stream_get_contents($a));
        $data = [
             'client_id' => $request->client_id,
             'case_id' => $request->case_id,
             'firm_id' => $request->firm_id,
             'file' => $file,
             'file_type' => $file_data->name   
        ];
    	ClientInformation::create($data);

         /*--------------------Notifications---------------*/ 
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' Firm admin Client Form submited';
        

        $client1 = Newclient::select('*')->where('id', $request->client_id)->first();

        $touser = User::where('id', $client1->user_id)->first();
        $n_link = url('firm/clientcase/show').'/'.$request->case_id;
        $message = collect(['title' => 'Firm Admin Client Form submited', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/case/case_forms').'/'.$request->case_id;
        $message = collect(['title' => 'Firm Admin Client Form submited', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $request->case_id)
                        ->first();
        $usercase = User::where('id', $FirmCase->id)->first();
        if($usercase->id != $task->firm_admin_id) {
            Notification::send($usercase, new DatabaseNotification($message));
        }
        Notification::send($touser, new DatabaseNotification($message)); 
        /*--------------------Notifications---------------*/ 

    	return redirect('firm/forms/'.$request->client_id)->with('success','Client Form submited successfully!');
    }

    public function getForms(Request $request) {
        $query = $request->q;

        $data = json_encode(
            [
                'query' => $query
            ]
        );

        $response = $this->api_client->request(
            'POST', '/2/files/search_v2',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('DROPBOX_TOKEN'),
                    'Content-Type' => 'application/json'
                ],
                'body' => $data
        ]);

        $search_results = json_decode($response->getBody(), true);
        $matches = $search_results['matches'];
        $res = array();
        if($matches) {
            foreach ($matches as $k => $v) {
                $metadata = $v['metadata']['metadata'];
                extract($metadata);
                $res[] = array('Name' => $name, 'ID' => $metadata);
            }
        }
        echo json_encode($res);
    }

    public function information_update() {
        extract($_POST);
        $client_arr = array();
        $client = ClientInformation::select('*')->where('id', $id)->first();
        $addr = json_decode($client->residence_address);
        $pdf_data = json_decode($_POST['data']);
        foreach ($pdf_data as $k => $v) {
            if(!empty($v->value)) {
                if (strpos($v->name, 'FirstName')) {
                    $client_arr['first_name'] = $v->value;
                }
                if (strpos($v->name, 'LastName')) {
                    $client_arr['last_name'] = $v->value;
                }
                if (strpos($v->name, 'MiddleName')) {
                    $client_arr['middle_name'] = $v->value;
                }
                if (strpos($v->name, 'Mobile')) {
                    $client_arr['cell_phone'] = $v->value;
                }

                if (strpos($v->name, 'Country')) {
                    $countries = DB::table("countries")->where('name', $v->value)->first();
                    if(!empty($countries)) {
                        $addr['country'] = $countries->id;
                    }
                }

                if (strpos($v->name, 'State')) {
                    $regions = DB::table("regions")->where('name', $v->value)->first();
                    if(!empty($regions)) {
                        $addr['state'] = $regions->id;
                    }
                }

                if (strpos($v->name, 'City')) {
                    $cities = DB::table("cities")->where('name', $v->value)->first();
                    if(!empty($cities)) {
                        $addr['city'] = $cities->id;
                        $addr['state'] = $cities->region_id;
                    }
                }

                if (strpos($v->name, 'Street')) {
                    $addr['address'] = $v->value;
                }
            }
        }
        if(!empty($addr)) {
            $client_arr['residence_address'] = json_encode($addr);
        }
        if(!empty($client_arr)) {
            
            //Newclient::where('id', $client->client_id)->update($client_arr);
        }
        
        
        
        
        // updatePdfFormAllField($id,$data);
        
        
        $data1 = array('information' => $data, 'status' => 0);
        ClientInformation::where('id', $id)->update($data1);
        // AdminTask::where('id', $request->task_id)->update($data);
    }

    public function update_form_status(Request $request) {
        $data1 = array('status' => $request->status);
        ClientInformation::where('id', $request->doc_id)->update($data1);
    }
}