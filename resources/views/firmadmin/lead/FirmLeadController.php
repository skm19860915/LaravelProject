<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use App\Models\Lead;
use App\Models\Log;
use App\Models\Firm;
use App\Models\Esubscription;
use App\Models\Event;
use App\Models\Newclient;



use App;

class FirmLeadController extends Controller
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
        return view('firmadmin.lead.index');
    }


    public function getData()
    { 
        $data = Auth::User();
        $lead = Lead::select('lead.*')
        ->where('firm_id',$data->firm_id)
        ->get();   
        foreach ($lead as $key => $value) {
            switch ($value->status) {
                case 0:
                    $value->status = "Lost Lead";
                    break;
                case 1:
                    $value->status = "In Processing";
                    break;
                case 2:
                    $value->status = "Convert To client";
                    break;
            }
        }

        return datatables()->of($lead)->toJson();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('firmadmin.lead.create');
    }


    public function create_lead(Request $request)
    {
        $file_arr1 = array();
        $file_arr1 = $request->document_path;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'string|email|unique:users|unique:lead'       
        ]);

        if ($validator->fails()) {
            return redirect('firm/lead')->withInfo('Mendatory fields are required!');
        }

        $requestData = $request->all();
        $requestData['document_path'] = '';
        $firm_id = Auth::User()->firm_id;
        $lead = Lead::create($requestData);
        Lead::where('id', $lead->id)->update(['firm_id' => $firm_id]);

        /* Document image upload start */
        if(!empty($file_arr1))
        {
            $file_arr = array();
            foreach ($file_arr1 as $k => $v) {
                $file_arr[] = Storage::put('lead_doc', $v);
                 
            }
            if($file_arr){
                $file_arr2 = json_encode($file_arr);
                Lead::where('id', $lead->id)->update(['document_path' => $file_arr2]);
            } 
        }
        /* Document image upload close */

        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();

        $log_data = [
            'title' => "LEAD_CREATE",
            'related_id' => $lead->id,
            'message' => $firm_name->firm_name." created a new lead ".$lead->name
        ];

        Log::create($log_data);

        if (!empty($request->email)) {

            $validator = Validator::make($request->all(), [
                'email' => 'string|email|unique:esubscription'       
            ]);

            if (!$validator->fails()) {
                
                $esubscription_data = [
                    'name' => $request->name,
                    'email' => $request->email
                ];
                Esubscription::create($esubscription_data);
            
            }
        }


        $logdata = [
            'title' => "FIRM",
            'related_id' => Auth::User()->firm_id,
            'message' => "Firm admin create a Lead ".$firm_name->firm_name
        ];
        Log::create($logdata);

        if ($lead) {
            return redirect('firm/lead')->withInfo('Firm lead created successfully!');
        }else{
            return redirect('firm/lead')->withInfo(' not created, please try again');
        }

        /*if ($lead_profile_id->id) {
            return redirect('firm/lead')->withInfo('Firm lead created successfully!');
        }else{
            return redirect('firm/lead')->withInfo('lead not created, please try again');
        }*/
    }


    public function edit($id)
    {

        $lead = Lead::where('id', $id)->first();
        return view('firmadmin.lead.edit',compact('lead'));
    }


    public function update_lead(Request $request)
    {

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'cell_phone' => $request->cell_phone,
            'home_phone' => $request->home_phone,
            'dob' => $request->dob,
            'language' => $request->language,
            'Current_address' => $request->Current_address,
            'lead_note' => $request->lead_note
        ];
        
        Lead::where('id', $request->lead_id)->update($data);
        return redirect('firm/lead')->withInfo('Firm lead update successfully!');
    }


    public function show($id)
    {

        $lead = Lead::where('id', $id)->first();
        return view('firmadmin.lead.show',compact('lead'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        Lead::where('id', $id)->delete();
        return redirect('firm/lead')->withInfo('Firm lead deleted successfully!');
    }

    
    public function lost($id)
    {
        Lead::where('id', $id)->update(['status' => 0]);
        return redirect('firm/lead')->withInfo('Firm Lead Lost');
    }
    




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_event($id)
    {
        return view('firmadmin.lead.create_event',compact('id'));
    }


    public function create_lead_event(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time' => 'required'     
        ]);

        if ($validator->fails()) {
            return redirect('firm/create_event')->withInfo('Mendatory fields are required!');
        }

        $lead_event_data = [
            'title' => "LEAD",
            'related_id' => $request->lead_id,
            'date' => $request->date,
            'time' => $request->time,
            'attorney' => Auth::User()->id
        ];

        $event = Event::create($lead_event_data);

        if ($event) {
            return redirect('firm/lead')->withInfo('Lead Event created successfully!');
        }else{
            return redirect('firm/create_event')->withInfo(' not created, please try again');
        }

        
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_client($id)
    {

        $lead = Lead::where('id', $id)->first();
        $pass = str_random(8);
        $data = [
            'name' => $lead->name,
            'role' => 6,
            'email' => $lead->email,
            'password' => Hash::make($pass),
            'password_confirmation' => Hash::make($pass),
            'role_id' => 6,
            'firm_id' => Auth::User()->firm_id
        ];
        
        $user = User::create($data);

        $data1 = [
            'first_name' => $lead->name,
            'user_id' => $user->id,
            'email' => $lead->email,
            'cell_phone' => $lead->cell_phone,
            'dob' => $lead->dob,
            'language' => $lead->language
        ];
        $newclient = Newclient::create($data1);
        // Newclient::where('id', $newclient->id)->update(['user_id' => $user->id, 'image_path' => $request->image_path]);


        if (true) {

            $username = $lead->name;
            $useremail =  $lead->email;
            $pass = $pass;

            $msg = "Hi, $username.<br>";
            $msg .= "Wellcome to TILA, your account hase been created successfully<br>";
            $msg .= "Please login to setup your account. Login details are given below <br>";
            $msg .= "Email : $useremail <br>";
            $msg .= "Password : $pass <br>";

            $args = array (
                'bodyMessage' => $msg,
                'to' => $useremail,
                'subject' => 'Wellcome to TILA',
                'from_name' => 'TILA',
                'from_email' => 'info@stoute.com'
            );
            send_mail($args);
        }

        Lead::where('id', $id)->update(['status' => 2]);
        
        if ($data) {
            return redirect('firm/client')->withInfo('Firm client created successfully!');
        }else{
            return redirect('firm/client')->withInfo('client not created, please try again');
        }
        // return view('firmadmin.lead.create_client',compact('lead','id'));
    }


    public function convert_client(Request $request)
    {

        /*pre($request->all());
        die();*/

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'email' => 'required|string|email|unique:users|unique:new_client',
        ]);

        if ($validator->fails()) {
            return redirect('firm/client')->withInfo('Mendatory fields are required!');
        }

        $pass = str_random(8);
        $data = [
            'name' => $request->first_name." ".$request->middle_name." ".$request->last_name,
            'role' => 6,
            'email' => $request->email,
            'password' => Hash::make($pass),
            'password_confirmation' => Hash::make($pass),
            'role_id' => 6,
            'firm_id' => Auth::User()->firm_id
        ];
        
        $user = User::create($data);
        $newclient = Newclient::create($request->all());
        Newclient::where('id', $newclient->id)->update(['user_id' => $user->id, 'image_path' => $request->image_path]);


        if ($request->is_portal_access == 1) {

            $username = $request->first_name." ".$request->middle_name." ".$request->last_name;
            $useremail =  $request->email;
            $pass = $pass;

            $msg = "Hi, $username.<br>";
            $msg .= "Wellcome to TILA, your account hase been created successfully<br>";
            $msg .= "Please login to setup your account. Login details are given below <br>";
            $msg .= "Email : $useremail <br>";
            $msg .= "Password : $pass <br>";

            $args = array (
                'bodyMessage' => $msg,
                'to' => $useremail,
                'subject' => 'Wellcome to TILA',
                'from_name' => 'TILA',
                'from_email' => 'info@stoute.com'
            );
            send_mail($args);
        }

        Lead::where('id', $request->lead_id)->update(['status' => 2]);
        
        if ($data) {
            return redirect('firm/client')->withInfo('Firm client created successfully!');
        }else{
            return redirect('firm/client')->withInfo('client not created, please try again');
        }

    }

}
