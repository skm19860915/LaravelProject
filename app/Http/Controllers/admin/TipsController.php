<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\HelpfullTips;
use App;
use DB;


class TipsController extends Controller
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
        return view('admin.tips.index', ["tips"=>$tips, 'total' => count($tips)]);
    }


    public function getData()
    { 
        $tips = HelpfullTips::select()->get();
        foreach ($tips as $key => $value) {
            $tips[$key]->stat = ($value->status == 1) ? "Active" : "Inactive";
        }
        return datatables()->of($tips)->toJson();        
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tips.create');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_tips(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'message' => 'required|string'
        ]);
        /*if ($validator->fails()) {
            return redirect('admin/helpfull_tips')->withInfo('Mendatory fields are required!');
            // return response(['success' => false, 'msg' => $msg]);
        }*/

        if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
        $data = [
            'title' => $request->title,
            'message' => $request->message
        ];
        
        $tips = HelpfullTips::create($data);

        if ($tips) {
            return redirect('admin/helpfull_tips')->with('success','Tips created successfully!');
        }
    }


    public function tips_show($id)
    {
        $tips = HelpfullTips::where('id', $id)->first();
        return view('admin.tips.tips_details', ["tips"=> $tips]);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tips_edit($id)
    {
        $tips = HelpfullTips::where('id', $id)->first();
        return view('admin.tips.tips_edit', ["tips"=> $tips]);
    }


    public function update_tips(Request $request)
    {
        
        $data = [
            'title' => $request->title,
            'message' => $request->message,
            'status' => $request->status
        ];

        HelpfullTips::where('id', $request->tips_id)->update($data);
        return redirect('admin/helpfull_tips')->with('success','Tips update successfully!');
    }


    public function delete($id)
    {
        
        HelpfullTips::where('id', $id)->delete();
        return redirect('admin/helpfull_tips')->with('success','Tips delete successfully!');
    }

}
