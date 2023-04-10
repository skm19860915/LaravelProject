<?php

namespace App\Http\Controllers\adminsupport;

use Illuminate\Mail\Mailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Mail;

use App\Models\SupportTicket;



class SupportDashboardController extends Controller
{
	public function index(Request $request)
	{
		//$currunt_user = Auth::User();

		$counter = [];

		$counter['total_ticket'] = SupportTicket::count();
		$counter['my_ticket'] = SupportTicket::where('supporter_id',Auth::User()->id)->count();
		$counter['completed_ticket'] = SupportTicket::where('supporter_id',Auth::User()->id)->where('status',3)->count();
		$counter['pending_ticket'] = SupportTicket::where('supporter_id',Auth::User()->id)->where('status',2)->count();

		return view('admin.adminsupport.dashboard.index',compact('counter'));
	}
}