<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use App\Models\Lead;
use App\Models\Log;
use App\Models\Firm;
use App\Models\Esubscription;
use App\Models\Event;
use App\Models\Newclient;
use App\Models\FirmSetting;
use App\Models\Transitions;
use App\Models\FirmCase;
use App\Models\Country;
use App\Models\LeadNotes;
use App\Models\AdminTask;

use Carbon\Carbon;

use App\Notifications\DatabaseNotification;
use Notification;

use App;
use DB;

class FirmTrasitionController extends Controller
{
    public function __construct()
    {
        
    }

    public function transition($id) {
    	return view('firmadmin.transition.index', compact('id'));
    }

    public function getDataTransition($id)
    { 
        
        $transitions = Transitions::select('*')
        ->where('client_id', $id)
        ->get();   
        foreach ($transitions as $key => $value) {
        	if($value->can_tila_contact) {
        		$transitions[$key]->can_tila_contact = 'Yes';
        	}
        	else {
        		$transitions[$key]->can_tila_contact = 'No';
        	}
        }
        return datatables()->of($transitions)->toJson();        
    }

    public function create($id) {
    	return view('firmadmin.transition.create', compact('id'));
    }

    public function create_transition(Request $request) {
    	$client = Newclient::where('id', $request->client_id)->get();
    	$validator = Validator::make($request->all(), [
    		'document' => 'required',
    		'language' => 'required|string',
            'can_tila_contact' => 'required|string',
            'client_id' => 'required|string'
    	]);
        if ($validator->fails()) {
        	return redirect()->back()->withErrors($validator);
        }
    	$document = Storage::put('client_doc', $request->document);

    	$client = Newclient::where('id', $request->client_id)->get();
    	$firm_id = Auth::User()->firm_id;
        $data = [
            'client_id' => $client[0]->user_id,  
            'firm_id' => $firm_id,
            'status' => 5
        ];

        $case = FirmCase::create($data);

    	$data = [
    		'document' => $document,
    		'language' => $request->language,
    		'can_tila_contact' => $request->can_tila_contact,
    		'client_id' => $request->client_id,
    		'case_id' => $case->id
    	];
    	Transitions::create($data);
        Firm::where('id', $firm_id)->update(['translation' => 1]);
    	$data1['case_id'] = $case->id;	
		$data1['firm_admin_id'] = Auth::User()->id;
		$data1['task_type'] = 'provide_a_quote';
		$data1['task'] = 'Provide a quote';
		$data1['status'] = 0;
		AdminTask::create($data1);

    	return redirect('firm/transition/'.$request->client_id)->with('success','Translation created successfully!');
    }
}