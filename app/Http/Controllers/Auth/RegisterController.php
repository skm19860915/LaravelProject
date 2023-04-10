<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\Firm;
use App\Models\MasterFirmEmailTemplate;
use App\Models\FirmSetting;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            //'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $pass = str_random(8);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => 4,
            'role' => 4,
            'password' => Hash::make($pass),
            'password_confirmation' => Hash::make($pass),
            'firm_id' => 0
        ]);

        $user->assignRole('Firm Admin');
        $doamin = url('');
        $llink = '<a href="'.url('login').'">Login Page</a>';
        $username = $data['name'];
        $useremail =  $data['email'];
        $msg1 = "Hello $username <br><br>";
        //$msg1 .= "Email : $useremail <br><br>";
        $msg1 .= "Thanks so much for signing up to TILA Case Prep; The Immigration Legal Assistant.<br><br>";
        $msg1 .="Welcome to your virtual journey. We are beyond excited to be of assistance to you and a supporting role to your legal team. To complete your account registration please follow the link below:<br><br>";
        //$msg1 .= "We are beyond excited to be of assistance to you and a supporting role to your legal team. To complete your account registration please follow the link below:<br><br>";
        $msg1 .= "1. Email: $useremail <br>";
        $msg1 .= "2. Temporary Password:  $pass <br>";
        $msg1 .= "3. Sign-up: $llink <br><br>";
        $msg1 .= "We are confident you will love your platform as much as we do!<br><br>";

        $msg1 .= "Sincerely,<br><br>";

        $msg1 .= "TILA Case Prep<br><br>";

        $msg1 .= "P.S. We are continuously working hard to make it a better experience for you and your team. If you feel encouraged to provide feedback, we would love to hear it!<br>";

        $msg1 .= "feedback@tilacaseprep.com.";
        $msg = EmptyEmailTemplate($msg1);
        $args = array (
            'bodyMessage' => $msg,
            'to' => $useremail,
            'subject' => 'Welcome to TILA Case Prep!',
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);

        $data = [
            'firm_name' => $user->name,
            'account_type' => 'VP Services',
            'email' => $user->email,
            'firm_admin_name' => $user->name,
            'usercost' => 65,
            'status' => 1
        ];
        $Firm = Firm::create($data);
        User::where('id', $user->id)->update(['firm_id' => $Firm->id]);

        $master_template = MasterFirmEmailTemplate::get();
        $master_record = [];

        foreach ($master_template as $key => $value) {
            $master_record = [
                'firm_id' => $Firm->id,
                'category' => "EMAIL",
                'title' => $value->title,
                'message' => $value->message
            ];
            FirmSetting::create($master_record);    
        }
        //Auth::logout();
        // return redirect('login')->withInfo('You are registered successfully please login');
        return $user;
    }
}
