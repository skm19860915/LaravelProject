<?php
namespace App\Http\Controllers\adminsupport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\SupportTicket;
use App\User;
use Twilio\Rest\Client;

class AllTicketController extends Controller
{
	
	public function index(Request $request)
	{
		return view('admin.adminsupport.allsupport.index');
	}

	
	public function getData()
	{
		$ticket = SupportTicket::select('support_ticket.*','roles.name as role_name','users.name as username','supporter.name as supportername')
        ->join('roles', 'support_ticket.by_role_id', '=', 'roles.id')
		->join('users', 'support_ticket.by_user_id', '=', 'users.id')
		->leftjoin('users as supporter', 'support_ticket.supporter_id', '=', 'supporter.id')
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
		->leftjoin('users as supporter', 'support_ticket.supporter_id', '=', 'supporter.id')
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

	  	return view('admin.adminsupport.allsupport.show',compact('ticket'));
	}



	public function accept($id)
	{

		$authUser_email = Auth::user()->email;
        $ticket_record = SupportTicket::select('by_user_id')->where('id',$id)->first();
        $user_email = User::select('email')->where('id',$ticket_record->by_user_id)->first();
        $otherUser_email = $user_email->email;
        $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));

        $ids = "Support-".$id;

        /*$temp_ids = explode('-', $ids);
        if($temp_ids[0] > $temp_ids[1]) {
            $ids = $temp_ids[1].'-'.$temp_ids[0];
        }*/
        // Fetch channel or create a new one if it doesn't exist
        try {
            $channel = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels($ids)
                ->fetch();
        } catch (\Twilio\Exceptions\RestException $e) {
            $channel = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels
                ->create([
                    'uniqueName' => $ids,
                    'type' => 'private',
                ]);
        }

        // Add first user to the channel
        try {
            $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels($ids)
                ->members($authUser_email)
                ->fetch();
        } catch (\Twilio\Exceptions\RestException $e) {
            $member = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels($ids)
                ->members
                ->create($authUser_email);
        }

        // Add second user to the channel
        try {
            $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels($ids)
                ->members($otherUser_email)
                ->fetch();
        } catch (\Twilio\Exceptions\RestException $e) {
            $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels($ids)
                ->members
                ->create($otherUser_email);
        }

        $data = [
            'supporter_id' => Auth::User()->id,
            'status' => 2,
            'twilio_id' => $ids
        ];


	    SupportTicket::where('id', $id)->update($data);
	    return redirect('admin/allsupport')->with('success','Ticket Allot Successfully successfully!');
	}
}

