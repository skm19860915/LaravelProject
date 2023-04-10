<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Firm;
use App\Models\FirmSetting;
use App\Models\UserMeta;

use App;
use DB;

class FirmUserController extends Controller
{
    public function __construct()
    {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_deleted = $request->session()->get('user_deleted');
        $request->session()->forget('user_deleted');
        $data = Auth::User();
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

        return view('firmadmin.users.index', compact('user_deleted', 'firm', 'card', $card, 'data'));
    }



    public function getData()
    { 
        $data = Auth::User();
        $users = User::select('users.*','roles.name as role_name')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('firm_id',$data->firm_id)
        // ->where('users.id', '!=', $data->id)
        ->whereIn('role_id', ['4', '5'])
        ->get();
        foreach ($users as $k => $v) {
            $users[$k]->custom_role = get_user_meta($v->id, 'custom_role');
        }
        return datatables()->of($users)->toJson();
        
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $current_firm_id = Auth::User()->firm_id;
        $firm = Firm::select('*')
        ->where('id',$current_firm_id)
        ->first();
        return view('firmadmin.users.create', compact('firm'));
    }

    public function addnewuser()
    {
        return view('firmadmin.users.addnewuser');
    }


    public function create_user(Request $request)
    {

        $current_firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', Auth::User()->firm_id)->first();
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string',
            'Role_type' => 'required',
            'email' => 'required|string|email|unique:users',
        ]);

        $pass = str_random(8);
        $role1 = $request->Role_type;
        if($request->Role_type == 8) {
            $role1 = 5;
        }
        $data = [
            'name' => $request->user_name,
            'role' => $role1,
            'email' => $request->email,
            'password' => Hash::make($pass),
            'password_confirmation' => Hash::make($pass),
            'role_id' => $role1,
            'firm_id' => Auth::User()->firm_id,
            'status' => 0
        ]; 
        if ($validator->fails()) {
                return redirect('firm/users/create')->with(['data' => $data])->withErrors($validator);
            }

        
        
        $user = User::create($data);
        if($request->Role_type == 8) {
            update_user_meta($user->id, 'custom_role', 'Attorney');
        }
        User::where('id', $user->id)->update(['first_login' => 0]);

        $request->session()->put('firm_user', $user);

        $logdata = [
            'title' => "FIRM",
            'related_id' => Auth::User()->firm_id,
            'message' => "Firm Admin create a User ".$request->user_name
        ];
        Log::create($logdata);

        
        if($firm->account_type == 'CMS') {
            $user_count = $request->session()->get('user_count');
            if(empty($user_count)) {
                $user_count = array();
            }
            $user->temp_pass = $pass;
            $user_count[] = $user;
            $request->session()->put('user_count', $user_count);
            
            if($request->session()->has('firstlogin')) {
                return redirect('firm/payment_method')->with('success','Firm User Create successfully');
            }
            else {
                return redirect('firm/payment_method')->with('success','Firm User Create successfully');

            }
        }
        else {
            $username = $request->user_name;
            $useremail =  $request->email;
            $pass = $pass;
            $LoginPage = url('login');

            $remove = array(
                'UserName' => $username,
                'Email'=>$useremail,
                'Password'=>$pass,
                'FirmName' => $firm->firm_name,
                'LoginPage' => $LoginPage
            );
            $email = EmailTemplate(45, $remove);
            
            $args = array(
                'bodyMessage' => $email['MSG'],
                'to' => $useremail,
                'subject' => $email['Subject'],
                'from_name' => 'TILA',
                'from_email' => 'no-reply@tilacaseprep.com'
            );

            send_mail($args);
            User::where('id',$user->id)->update(['status' => 1]);
            return redirect('firm/users')->with('success','Firm User Create successfully');
        }
    }

    public function create_user1(Request $request)
    {
        $current_firm_id = Auth::User()->firm_id;
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string',
            'Role_type' => 'required',
            'email' => 'required|string|email|unique:users',
        ]);

        $pass = str_random(8);
        $data = [
            'name' => $request->user_name,
            'role' => $request->Role_type,
            'email' => $request->email,
            'password' => Hash::make($pass),
            'password_confirmation' => Hash::make($pass),
            'role_id' => $request->Role_type,
            'firm_id' => Auth::User()->firm_id,
        ]; 
        if ($validator->fails()) {
            return redirect('firm/users/addnewuser')->with(['data' => $data])->withErrors($validator);
        }
        
        $pass = str_random(8);
        $data = [
            'name' => $request->user_name,
            'role' => $request->Role_type,
            'email' => $request->email,
            'password' => Hash::make($pass),
            'password_confirmation' => Hash::make($pass),
            'role_id' => $request->Role_type,
            'firm_id' => Auth::User()->firm_id,
        ];

        $user = User::create($data);
        User::where('id', $user->id)->update(['first_login' => 0]);
        $logdata = [
            'title' => "FIRM",
            'related_id' => Auth::User()->firm_id,
            'message' => "Firm Admin create a User ".$request->user_name
        ];
        Log::create($logdata);

        $message = FirmSetting::where('title','Welcome User')->where('category','EMAIL')->where('firm_id',$current_firm_id)->first();

        $username = $request->user_name;
        $useremail =  $request->email;
        $pass = $pass;

        $remove = array(
            'FirmName' => $username,
            'UserN'=>$useremail,
            'UserP'=>$pass,
        );
        $email = EmailTemplate(32, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => $useremail,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );

        // send_mail($args);   
        
        $user_count = $request->session()->get('user_count');
        if(empty($user_count)) {
            $user_count = array();
        }
        $user->temp_pass = $pass;
        $user_count[] = $user;
        $request->session()->put('user_count', $user_count);

        if($request->create_firm_user == 'Create and Add More') {
            return redirect('firm/users/addnewuser')->with('success','Firm User Create successfully');
        }
        else {
            return redirect('firm/payment_method2')->with('success','Firm User Create successfully');

        }
    }

    public function createuser(Request $request)
    {
        $res = array();
        $res['status'] = false;
        $res['msg'] = '';
        $current_firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', Auth::User()->firm_id)->first();
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string',
            'Role_type' => 'required',
            'email' => 'required|string|email|unique:users',
        ]);

        $pass = str_random(8);
        $data = [
            'name' => $request->user_name,
            'role' => $request->Role_type,
            'email' => $request->email,
            'password' => Hash::make($pass),
            'password_confirmation' => Hash::make($pass),
            'role_id' => $request->Role_type,
            'firm_id' => Auth::User()->firm_id,
            'status' => 0
        ]; 
        if ($validator->fails()) {
                $res['msg'] = $validator->errors()->first();
                echo json_encode($res);
                die();
        }

        
        
        $user = User::create($data);

        User::where('id', $user->id)->update(['first_login' => 0]);
        $res['status'] = true;
        $res['msg'] = 'Firm User Create successfully';
        echo json_encode($res);
        die();
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $user = User::where('id', $id)->first();
        return view('firmadmin.users.edit', ["user"=>$user]);
    }


    public function update(Request $request)
    {

        if($request->oldemail == $request->email) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
            ]);
        }
        else {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
            ]);
        }
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($validator->fails()) {
            // $data['error'] = 'rk test';
            return redirect('firm/users/edit/'.$request->id)->with(['data' => $data])->withErrors($validator);
        }
        User::where('id',$request->id)->update(['name' => $request->name]);

        if($request->new_password)
        {
            User::where('id',$request->id)->update(['password' => Hash::make($request->new_password)]);
        }   
       if($request->oldemail != $request->email) { 
            $username = $request->user_name;
            $useremail =  $request->email;
            $msg1 = "Hi, $username.<br>";
            $msg1 .= "Email : $useremail <br><br>";
            $msg1 .= "Your Email has been updated successfully<br>";
            
            $msg = EmptyEmailTemplate($msg1);
            $args = array (
                'bodyMessage' => $msg,
                'to' => $useremail,
                'subject' => 'Wellcome to TILA',
                'from_name' => 'TILA',
                'from_email' => 'no-reply@tilacaseprep.com'
            );
            send_mail($args);
       }
        return redirect('firm/users')->with('success','User Update successfully!');
        // Firm::where('id', $_POST['id'])->update(['firm_name' => $_POST['firm_name'], 'account_type' => $_POST['account_type']]);
        // return redirect('admin/firm')->with('success','Firm Account update successfully!');

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request,$id)
    {
        PagesAuthentications();
        $userdata = User::select('*')->where('id', $id)->first();
        $userdata = DB::table('users')->where('id', $id)->first();
        $firm = Firm::where('id', $userdata->firm_id)->first();
        $date = date('Y-m-d');
        $created_at = date('Y-m-d', strtotime($userdata->created_at));
        // Declare and define two dates  , strtotime('+1 month')
        $date1 = strtotime($created_at); 
        $date2 = strtotime($date); 

        // Formulate the Difference between two dates 
        $diff = abs($date2 - $date1); 

        // To get the year divide the resultant date into 
        // total seconds in a year (365*60*60*24) 
        $years = floor($diff / (365*60*60*24)); 

        // To get the month, subtract it with years and 
        // divide the resultant date into 
        // total seconds in a month (30*60*60*24) 
        $months = floor(($diff - $years * 365*60*60*24) 
                                    / (30*60*60*24)); 

        // To get the day, subtract it with years and 
        // months and divide the resultant date into 
        // total seconds in a days (60*60*24) 
        $days = floor(($diff - $years * 365*60*60*24 - 
                    $months*30*60*60*24)/ (60*60*24)); 

        if($date == $created_at) {
            $days = 30;
        }
        if($days == 0 && $months != 0) {
            $days = 30;
        }
        $amt = ($firm->usercost)/30;

        $uarr = array(
                'userdata' => $userdata,
                'amt' => number_format($amt*$days, 2),
                'end_date' => $date
                // 'date1' => $created_at,
                // 'days' => $days,
                // 'amount' => $amt,
                );

        // pre($uarr);
        // die();
        $request->session()->put('user_deleted', $uarr);
        // User email 
        $username = $userdata->name;
        $useremail =  $userdata->email;
        $msg1 = "Hi, $username.<br>";
        $msg1 .= "Email : $useremail <br><br>";
        $msg1 .= $firm->firm_name." have been removed from your account effective ".$date.". If this is a mistake, please contact support@tilacaseprep.com to reinstate these users<br>";
        
        $msg = EmptyEmailTemplate($msg1);
        $args = array (
            'bodyMessage' => $msg,
            'to' => $useremail,
            'subject' => 'Wellcome to TILA',
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        // send_mail($args);

        // Firm Email 
        $username1 = $firm->firm_name;
        $useremail1 =  $firm->email;
        $msg1 = "Hi, $username1.<br><br>";

        $msg1 .= "You have been removed $username effective ".$date.". $".$firm->usercost." user monthly charge, and you will get refunded $".number_format($amt*$days, 2)."<br>";
        
        $msg = EmptyEmailTemplate($msg1);
        $args = array (
            'bodyMessage' => $msg,
            'to' => $useremail1,
            'subject' => 'Wellcome to TILA',
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);

        UserMeta::where('user_id', $id)->delete();
        User::where('id', $id)->delete();
        
        return redirect('firm/users')->with('success','Firm User deleted successfully!');

    }

    public function deletenew(Request $request, $id)
    {
        $firm_user = $request->session()->get('user_count');
        $newarr = array();
        if(!empty($firm_user)) {
            foreach ($firm_user as $k => $v) {
                if($v->id == $id) {
                    User::where('id', $id)->delete();
                }
                else {
                    $newarr[] = $v;
                }
            }
        }
        $request->session()->put('user_count', $newarr);

        $firm = DB::table('firms')->where('id', Auth::User()->firm_id)->first();
        $firm_user = $newarr;
        if(Auth::User()->role_id == '4' && $firm->account_type == 'CMS') {
          $Transaction = DB::table('transactions')->where('user_id', Auth::User()->id)->count();
          if($Transaction) {
              if(!empty($firm_user)) {
                return redirect('firm/payment_method')->with('success','User deleted successfully!');
              }  
              else {
                return redirect('firm/users')->with('success','User deleted successfully!');
              } 
          }
          else {
            return redirect('firm/payment_method2')->with('success','User deleted successfully!');
          }
          
        }
        //return redirect('firm/payment_method2')->with('success','User deleted successfully!');

    }

    public function update_new(Request $request)
    {
        $res = array();
        if($request->oldemail == $request->email) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
            ]);
        }
        else {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
            ]);
        }
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->Role_type, 
            // 'role' => $request->Role_type
        ];

        if ($validator->fails()) {
            $res['status'] = false;
            $res['msg'] = $validator->errors()->first();
            echo json_encode($res);
            die();
        }
        User::where('id',$request->id)->update($data);
        $firm_user = $request->session()->get('user_count');
        $newarr = array();
        if(!empty($firm_user)) {
            foreach ($firm_user as $k => $v) {
                if($v->id == $request->id) {
                    $firm_user[$k]->name = $request->name;
                    $firm_user[$k]->email = $request->email;
                    $firm_user[$k]->role_id = $request->Role_type;
                }
            }
        }
        $request->session()->put('user_count', $firm_user);
        $res['status'] = true;
        echo json_encode($res);
       if($request->oldemail != $request->email) { 
            $username = $request->user_name;
            $useremail =  $request->email;
            $msg1 = "Hi, $username.<br>";
            $msg1 .= "Email : $useremail <br><br>";
            $msg1 .= "Your Email has been updated successfully<br>";
            
            $msg = EmptyEmailTemplate($msg1);
            $args = array (
                'bodyMessage' => $msg,
                'to' => $useremail,
                'subject' => 'Wellcome to TILA',
                'from_name' => 'TILA',
                'from_email' => 'no-reply@tilacaseprep.com'
            );
            send_mail($args);
       }
    }
}
