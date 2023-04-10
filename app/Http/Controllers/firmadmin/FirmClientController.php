<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\User;
use App\Models\Client_profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use App;

class FirmClientController extends Controller
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
        return view('firmadmin.client.index');
    }


    public function getData()
    { 
        $data = Auth::User();
        $users = User::select('users.*')
        ->where('firm_id',$data->firm_id)
        ->where('role_id' ,'=', '6')
        ->get();   
        return datatables()->of($users)->toJson();
        
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = DB::table("countries")->get();
        pre($countries);
        die();
        return view('firmadmin.client.create',compact('countries'));
    }


    public function create_client(Request $request)
    {

        
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string',
            'petitioner_name' => 'required|string',
            'contact_phone' => 'required',
            'in_city' => 'required|string',
            'in_state' => 'required|string',
            'out_city' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'Client_notes' => 'required'
        ]);

        /*if ($validator->fails()) {
            return redirect('firm/client')->withInfo('Mendatory fields are required!');
        }*/

        if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

        $data = [
            'name' => $request->client_name,
            'role' => 6,
            'email' => $request->email,
            'password' => Hash::make($request->user_password),
            'password_confirmation' => Hash::make($request->user_password),
            'role_id' => 6,
            'firm_id' => Auth::User()->firm_id
        ];
        
        $user = User::create($data);


        $data1 = [
            'user_id' => $user->id,
            'client_name' => $request->client_name,  
            'client_dob' => $request->client_dob,
            'petitioner_name' => $request->petitioner_name,
            'petitioner_dob' => $request->petitioner_dob,
            'contact_phone' => $request->contact_phone,
            'in_city' => $request->in_city,
            'in_state' => $request->in_state,
            'out_city' => $request->out_city, 
            'out_state' => $request->out_state, 
            'note' => $request->Client_notes
        ];

        $client_profile_id = Client_profile::create($data1);
        

        /* Document image upload start */
        if(!empty($request->client_file))
        {
            $client_file = Storage::put('client_doc', $request->client_file);
            if($client_file){
                Client_profile::where('id', $client_profile_id->id)->update(['file_path' => $client_file]);
            }
        }
        /* Document image upload close */


        $username = $request->client_name;
        $useremail =  $request->email;
        $pass = $request->user_password;

        $msg = "Hi, $username.<br>";
        $msg .= "Wellcome to TILA, your account hase been created successfully<br>";
        $msg .= "Please login to setup your account. Login details are given below <br>";
        $msg .= "Email : $useremail <br>";
        $msg .= "Password : $pass <br>";
        $msg = EmptyEmailTemplate($msg);
        $args = array (
            'bodyMessage' => $msg,
            'to' => $useremail,
            'subject' => 'Wellcome to TILA',
            'from_name' => 'TILA',
            'from_email' => 'info@stoute.com'
        );
        send_mail($args);
        if ($data) {
            return redirect('firm/client/created')->withInfo('Firm client created successfully!');
        }else{
            return redirect('firm/client')->withInfo('client not created, please try again');
        }


        /*if ($client_profile_id->id) {
            return redirect('firm/client')->withInfo('Firm client created successfully!');
        }else{
            return redirect('firm/client')->withInfo('client not created, please try again');
        }*/

    }

    public function created()
    {
        return view('firmadmin.client.created');
    }

    public function show($id)
    {
        $client = Client_profile::where('user_id', $id)->first();
        return view('firmadmin.client.show',compact('client'));
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        echo $id;
        die();
        $client = User::where('id', $id)->first();
        return view('firmadmin.client.edit', ["firmclient"=>$client]);
    }


    public function update(Request $request)
    {
        

        Firm::where('id', $_POST['id'])->update(['firm_name' => $_POST['firm_name'], 'account_type' => $_POST['account_type']]);
        return redirect('admin/firm')->withInfo('Firm Account update successfully!');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        User::where('id', $id)->delete();
        return redirect('firm/client')->withInfo('Firm client deleted successfully!');
    }

    /*public function roles()
    {
        return response()->json(Role::get());
    }*/
}
