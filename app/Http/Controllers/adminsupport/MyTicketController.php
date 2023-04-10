<?php
namespace App\Http\Controllers\adminsupport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\SupportTicket;
use App\User;
use Twilio\Rest\Client;

class MyTicketController extends Controller
{
	public function index(Request $request)
	{
		return view('admin.adminsupport.mysupport.index');
	}



	public function getData()
	{ 
		$data = Auth::User();
		$ticket = SupportTicket::select('support_ticket.*','roles.name as role_name','users.name as username','supporter.name as supportername')
        ->join('roles', 'support_ticket.by_role_id', '=', 'roles.id')
		->join('users', 'support_ticket.by_user_id', '=', 'users.id')
		->leftjoin('users as supporter', 'support_ticket.supporter_id', '=', 'supporter.id')
		->where('support_ticket.supporter_id', $data->id)
		->get(); 

		foreach ($ticket as $key => $value) {
			
			$ticket[$key]->supportername = ($value->supportername == "") ? "NA" : $value->supportername;

            switch ($value->priority) {
                case 1:
                    $ticket[$key]->priority = "High";
                    break;
                case 2:
                    $ticket[$key]->priority = "Medium";
                    break;
                case 3:
                    $ticket[$key]->priority = "Low";
                    break;
            }

            switch ($value->status) {
                case 1:
                    $ticket[$key]->status = "Open";
                    break;
                case 2:
                    $ticket[$key]->status = "Process";
                    break;
                case 3:
                    $ticket[$key]->status = "Complete";
                    break;
            }
		}
        return datatables()->of($ticket)->toJson();        
    }


	public function show($id)
	{	     
	    $ticket = SupportTicket::select('support_ticket.*','roles.name as role_name','users.name as username')
        ->join('roles', 'support_ticket.by_role_id', '=', 'roles.id')
		->join('users', 'support_ticket.by_user_id', '=', 'users.id')
		->where('support_ticket.id', $id)
		->first();

		$ticket->supportername = ($ticket->supportername == "") ? "NA" : $ticket->supportername;

            switch ($ticket->priority) {
                case 1:
                    $ticket->priority = "High";
                    break;
                case 2:
                    $ticket->priority = "Medium";
                    break;
                case 3:
                    $ticket->priority = "Low";
                    break;
            }

            switch ($ticket->status) {
                case 1:
                    $ticket->status = "Open";
                    break;
                case 2:
                    $ticket->status = "Process";
                    break;
                case 3:
                    $ticket->status = "Complete";
                    break;
            }
	  	return view('admin.adminsupport.mysupport.show',compact('ticket'));
	}

    public function chat($id)
    {
       
        SupportTicket::where('id', $id)->update($data);
        return redirect('admin/allsupport')->with('success','Ticket Allot Successfully !');

    }

}