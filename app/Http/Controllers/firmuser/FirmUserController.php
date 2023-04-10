<?php

namespace App\Http\Controllers\firmuser;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Log;


use App;

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
        return view('firmadmin.firmuser.users.index');
    }



    public function getData()
    { 
        $data = Auth::User();
        $users = User::select('users.*','roles.name as role_name')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('firm_id',$data->firm_id)
        ->where('users.id', '!=', $data->id)
        ->where('role_id' ,'=', '5')
        ->get();
        return datatables()->of($users)->toJson();
        
    } 
}
