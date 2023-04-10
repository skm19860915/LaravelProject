<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Models\Log;
use App\Models\FirmCase;
use App\Models\Lead;
use App\Models\Event;
use App\User;

use DB;
use App;

class FirmProfileController extends Controller
{
    public function __construct()
    {

    }
    

    public function index()
    {
        $user = Auth::User();
        $countries = DB::table("countries")->get();
        return view('firmadmin.profile.edit_profile',compact('user','countries'));
    }


    public function update_profile(Request $request)
    {
        
        $user_id = Auth::User()->id;
        $requestData = $request->all();
        $requestData['address'] = json_encode($request->birth_address1);

        $data = [
            'contact_number' => $requestData['contact_number'],
            'address' => $requestData['address']
        ];

        $check = User::where('id',$user_id)->update($data);

        if ($check) {
            return redirect('firm/profile')->with('success','Profile Updated successfully!');
        }else{
            return redirect('firm/profile')->with('error','Profile not updated, please try again');
        }

    }


    
}
