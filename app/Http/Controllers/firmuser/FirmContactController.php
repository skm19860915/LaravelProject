<?php

namespace App\Http\Controllers\firmuser;

use Illuminate\Http\Request;
use App\User;
use App\Models\Client_profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client; 
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
        return view('firmadmin.firmuser.contact.index');
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
        return view('firmadmin.firmuser.contact.show',compact('client'));
    }

    public function send_message(Request $request)
    {
        $user = User::select('users.*','new_client.*')
        ->join('new_client', 'users.id', 'new_client.user_id')
        ->where('users.id',$request->user_id)
        ->where('new_client.id' , $request->client_id)
        ->get();   
        $msg = $request->message;

        $contact_phone = $user[0]->contact_phone;

        if(!empty($contact_phone)) {
            $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));

            $message = $twilio->messages 
                  ->create("+919893926705", // to 
                           array( 
                             "from" => "+19282321872",       
                             "body" => "Your message" 
                         ) 
                       ); 

        }

        if($request->is_email_send) {
            $useremail = $user->email;
            $msg1 = EmptyEmailTemplate($msg);
            $args = array (
                'bodyMessage' => $msg,
                'to' => $useremail,
                'subject' => 'Welcome to TILA Case Prep',
                'from_name' => 'TILA',
                'from_email' => 'info@stoute.com'
            );
            send_mail($args);
        }

        return redirect('firm/firmcontacts/show/'.$request->user_id)->with('success','Send message to client successfully!');
    }
}