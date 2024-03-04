<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GenUser;
use App\Models\Conversation;
use GuzzleHttp\Exception\InvalidArgumentException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Events\ReceivedRequest;


use App\Mail\NotifyMail;


class SendEmailController extends Controller
{
    
    public function index()
    {
        $conversationId = request('conversationid');
        $document = request('documentid');
        try {
            //from details -- agent
            $agent = DB::table('gen_users')
            ->join('conversations','conversations.user_id','=','gen_users.user_id')
            ->where('conversation_id',$conversationId)
            ->select('gen_users.*')
            ->first();
            Session::put('access_token', $agent->access_token);
            
            //to details -- customer
            $genesys = new GenesysController();   
            $conversation = $genesys->getUserConversations($conversationId);
            //dd($conversation);
            $customer = $genesys->getParticipant($conversation->participants, "customer");      
            
            $customer = $conversation->participants[0]->messages[0]->fromAddress;
    
            $data["agent-email"] = $agent->email;
            $data["agent-name"] = $agent->name;
            $data["customer-email"] = $customer->addressNormalized;
            $data["customer-name"] = $customer->name;
            $data["title"] = "Info";
            $data["body"] = "Please refer to the below document.";
            
            $file = public_path().'/storage/files/'.$document;
        
        
            Mail::send('emails.demoMail', $data, function($message)use($data, $file) {
                $message->to($data['customer-email'], $data['customer-name'])
                ->replyTo($data['agent-email'],$data['agent-name'])
                ->subject($data["title"]);
                
                $message->attach($file);
            });
        } catch(\Throwable $e){
            return $e->getMessage();
        }
            
        if (Mail::failures()) {
            return "error";
        } else{
            return "success";
        }
    }
}
