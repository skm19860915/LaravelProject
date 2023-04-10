<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\User;
use App\Models\Client_profile;
use App\Models\Firm;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client; 
use App\Models\TextMessage;
use App;
use DB;
class FirmContactController extends Controller
{
    public function __construct()
    {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Auth::User();
        $users = User::select('users.*','new_client.*')
        ->join('new_client', 'users.id', 'new_client.user_id')
        ->where('users.firm_id',$data->firm_id)
        ->where('users.role_id' ,'=', '6')
        ->get(); 

        $firm = Firm::select('*')
        ->where('id',$data->firm_id)
        ->first();
        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
        $searchResults = \Stripe\Customer::all([
            "email" => $data->email,
            "limit" => 1,
            "starting_after" => null
        ]);
        $cust = '';
        $card = '';
        if($searchResults->data) {
            $cust =  $searchResults->data[0];
            $card = $cust->sources->data;
        }
        $users1 = User::select('users.*','roles.name as role_name')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('firm_id',$data->firm_id)
        ->whereIn('role_id', ['4', '5'])
        ->get();
        return view('firmadmin.contact.index', compact('users', 'firm', 'card', 'data', 'users1'));
    }


    public function getData()
    { 
        $data = Auth::User();
        $users = User::select('users.*','new_client.*')
        ->join('new_client', 'users.id', 'new_client.user_id')
        ->where('users.firm_id',$data->firm_id)
        ->where('users.role_id' ,'=', '6')
        ->get();   
        return datatables()->of($users)->toJson();
        
    }

    public function show($id)
    {
        //$client = Client_profile::where('user_id', $id)->get();
        $client = User::select('users.*','new_client.*')
        ->join('new_client', 'users.id', 'new_client.user_id')
        ->where('users.id',$id)
        ->first(); 
        // pre($client);
        return view('firmadmin.contact.show',compact('client'));
    }

    public function send_message(Request $request)
    {
        $user = User::select('users.*','new_client.*')
        ->join('new_client', 'users.id', 'new_client.user_id')
        // ->where('users.id',$request->user_id)
        ->where('new_client.id' , $request->client_id)
        ->get();   
        $msg = $request->message;
        $subject = $request->subject;
        // pre($request->all());
        // die();
        $mtype = array();
        $contact_phone = $user[0]->contact_phone;

        if(!empty($request->is_text_send)) {
            $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));
            $phone_no = $request->phone_no;
            $phone_no = preg_replace('/(.*) \((.*)\) (.*)-(.*)/', '$1$2$3$4', $phone_no);
            try {
                $message = $twilio->messages 
                  ->create($phone_no,
                           array( 
                             "from" => env('TWILIO_FROM_NO'),       
                             "body" => $msg 
                         ) 
                       ); 
            }
            catch (\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
            $mtype[] = 'SMS';
        }

        if(!empty($request->is_email_send)) {
            $useremail = $request->email;
            $msg1 = EmptyEmailTemplate($msg);
            $args = array (
                'bodyMessage' => $msg1,
                'to' => $useremail,
                'subject' => $subject,
                'from_name' => 'TILA',
                'from_email' => 'info@stoute.com'
            );
            send_mail($args);
            $mtype[] = 'Email';
        }

        $data2 = [
            'msgfrom' => Auth::User()->id,
            'msgto' => $user[0]->user_id,
            'msg' => $msg,
            'subject' => $subject,
            'type' => json_encode($mtype)
        ];
        TextMessage::create($data2);
        return redirect('firm/contacts')->with('success','Send message to client successfully!');
    }
}