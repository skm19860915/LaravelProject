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
use App\Models\Newclient;
use App;

class FirmClientController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        return view('firmadmin.firmuser.client.index');
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

    public function show($id)
    {
        $client = Newclient::where('user_id', $id)->first();
        return view('firmadmin.firmuser.client.show',compact('client'));
    }
}