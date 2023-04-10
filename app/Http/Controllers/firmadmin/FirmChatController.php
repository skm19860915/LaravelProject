<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\User;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class FirmChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data = Auth::User();
        $users = User::where('firm_id', '=',$data->firm_id)->where('id', '!=', $data->id)->orWhere('role_id', '=',2)->get();
        return view('firmadmin.chat.index', compact('users'));
    }

    public function chat(Request $request, $ids)
    {
        $authUser = $request->user();
        $data = Auth::User();
        $otherUser = User::find(explode('-', $ids)[1]);
        
        $users = User::where('firm_id', '=',$data->firm_id)->where('id', '!=', $data->id)->orWhere('role_id', '=',2)->get();

        $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));

        // $messages = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
        //                             ->channels("CHa8846abad94b439c99e14815463ad04b")
        //                             ->messages
        //                             ->read(array('order' => 'desc'), 5);
        // foreach ($messages as $key => $message) {
        //     pre($message->from);
        //     pre($message->body);
        // }
        
        // $ids = strtolower(base64_encode($data->firm_id));

        $temp_ids = explode('-', $ids);
        if($temp_ids[0] > $temp_ids[1]) {
            $ids = $temp_ids[1].'-'.$temp_ids[0];
        }
        
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
                ->members($authUser->email)
                ->fetch();
        } catch (\Twilio\Exceptions\RestException $e) {
            $member = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels($ids)
                ->members
                ->create($authUser->email);
        }

        // Add second user to the channel
        try {
            $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels($ids)
                ->members($otherUser->email)
                ->fetch();
        } catch (\Twilio\Exceptions\RestException $e) {
            $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels($ids)
                ->members
                ->create($otherUser->email);
        }

        return view('firmadmin.chat.chat', compact('users', 'otherUser'));
    }
}
