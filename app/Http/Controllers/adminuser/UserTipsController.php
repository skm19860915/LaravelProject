<?php

namespace App\Http\Controllers\adminuser;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\HelpfullTips;
use App;
use DB;


class UserTipsController extends Controller
{
    public function __construct()
    {
        //$this->authorizeResource(User::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tips = HelpfullTips::select()->get();
        return view('admin.adminuser.usertips.index', ["tips"=>$tips, 'total' => count($tips)]);
    }


    public function getData()
    { 
        $tips = HelpfullTips::select()->get();
        foreach ($tips as $key => $value) {
            $tips[$key]->stat = ($value->status == 1) ? "Active" : "Inactive";
        }
        return datatables()->of($tips)->toJson();        
    }



    public function tips_show($id)
    {
        $tips = HelpfullTips::where('id', $id)->first();
        return view('admin.adminuser.usertips.tips_details', ["tips"=> $tips]);
    }

}
