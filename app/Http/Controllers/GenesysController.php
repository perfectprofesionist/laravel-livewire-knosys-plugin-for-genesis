<?php
namespace App\Http\Controllers;

use GuzzleHttp\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Events\ReceivedRequest;
use App\Models\GenUser;
use App\Models\Conversation;
use App\Models\Settings;

class GenesysController extends Controller
{
    public $settings;
    public function getSettings($name){
        if($this->settings == null){
            $this->settings = array();
            foreach(Settings::where('section','general')->get() as $set){
                $this->settings+=[$set['name']=>$set];
            }
        }
        return $this->settings[$name]->value;
    }

    public function getResource()
    {
        $resource_url = "/api/v2/conversations/messaging/integrations/open";
        $header = ["Authorization" => "Bearer " . session('access_token')];

        $response = $this->callAPI('GET', $resource_url, null, $header);

        return $response;
    }

    public function sendMessage()
    {
        
        $conv = Conversation::where('conversation_id',request('conversationid'))->first();
        $knosys = new KnosysController();
        $knosys->loadConversationUser($conv->conversation_id);
        $header = ["Authorization" => "Bearer " . session('access_token')];
        
        $conversation = $this->getConversation($conv->conversation_id, session('access_token'));
        Log::info("Sending to conversation".$conv->conversation_id);
        try {
            $communicationId = $this->getParticipant($conversation[0]->participants, "agent")->messages[0]->id;
        }
        catch (\Throwable $e){
            return $this->returnErrorToView($conv, Settings::where('section','display')->where('name','ERR_MSG_NOT_SENT')->first()->value);
        }
        $date = Carbon::now()->toW3cString();
        if(request('toMessage')=="DocType" ){
            if(request('docId')==null){
                $docId = request('docId');
                /* $document = //getdocument
                $uploadMediaURL = "/api/v2/conversations/messages/{$conv->conversation_id}/communications/{$communicationId}/messages/media";
                
                $req_body = [
                    "id" => $docId,
                    "name" => "4a30f651-ba-genesys-logo-color.png",
                    "url" => "https://www.genesys.com/media/4a30f651-ba-genesys-logo-color.png",
                    "mediaType" => "image/png",
                    "contentLengthBytes" => "1132"
                ];
                $response = $this->callAPI('POST', $uploadMediaURL, $req_body, $header);
                
                $sendMediaURL = "/api/v2/conversations/messages/{$conv->conversation_id}/communications/{$communicationId}/messages";
                
                $media = [
                    "timestamp" => $date,
                    "mediaIds"=>[
                        "MediaId1",
                        "MediaId2"
                    ]];
                
                $response = $this->callAPI('POST', $sendMediaURL, $media, $header); */
                return view('homekno7')->with('success', 'Document send successfully');
            } else {
                Log::info("Document details not received");
            }
        } else {
        $postConversationURL = "/api/v2/conversations/messages/{$conv->conversation_id}/communications/{$communicationId}/messages";
        Log::info("Sending message to ::".$postConversationURL);
        

        $req_body = [
            "direction" => "outbound",
            "timestamp" => $date,
            "textBody" => request('toMessage')
        ];

        $response = $this->callAPI('POST', $postConversationURL, $req_body, $header);
       
        return $this->returnSuccessToView($conv, Settings::where('section','display')->where('name','SCCS_MSG_SENT')->first()->value);
        }
    }
    
    public function sendDocument($docName){
        $conv = Conversation::where('conversation_id',request('conversationid'))->first();
        $knosys = new KnosysController();
        $knosys->loadConversationUser($conv->conversation_id);
        $header = ["Authorization" => "Bearer " . session('access_token')];
        
        $conversation = $this->getConversation($conv->conversation_id, session('access_token'));
        
        $communicationId = $this->getParticipant($conversation[0]->participants, "agent")->messages[0]->id;
        $uploadMediaURL = "/api/v2/conversations/messages/{$conv->conversation_id}/communications/{$communicationId}/messages/media";
        $date = Carbon::now()->toW3cString();
        
        $req_body = [
            "id" => "<MediaId>",
            "name" => "4a30f651-ba-genesys-logo-color.png",
            "url" => "https://www.genesys.com/media/4a30f651-ba-genesys-logo-color.png",
            "mediaType" => "image/png",
            "contentLengthBytes" => "1132"
        ];
        $response = $this->callAPI('POST', $uploadMediaURL, $req_body, $header);
        
        $sendMediaURL = "/api/v2/conversations/messages/{$conversationId}/communications/{$communicationId}/messages";
        
        $media = [
            "timestamp" => $date,
            "mediaIds"=>[
                "MediaId1",
                "MediaId2"
            ]];
        
        $response = $this->callAPI('POST', $sendMediaURL, $media, $header);
    }

    public function getConversation($conversationId, $token)
    {
        $response = event(new ReceivedRequest($conversationId, $token));
        $stdout = fopen('php://stdout', 'w');
        fwrite($stdout, "Token " . $token . PHP_EOL);
        fwrite($stdout, "ConvId " . $conversationId . PHP_EOL);
        return $response;
    }

    public function loadOpenMessages($response, $token)
    {
        $customer = $this->getParticipant($response[0]->participants, 'customer');
        // $context = $this->getContext(get_object_vars($customer->attributes));
        $header = ["Authorization" => "Bearer " . $token];
        $messages = array();
        foreach ($customer->messages[0]->messages as $msg) {
            $customer_message = $this->callAPI('GET', $msg->messageURI, null, $header);
            array_push($messages, [
                'timestamp' => $customer_message->timestamp,
                'text' => $customer_message->textBody
            ]);
        }
        $messages = collect($messages)->sortBy('timestamp')
            ->reverse()
            ->toArray();
        session([
            'customer_messages' => $messages
        ]);
        Session::put('customer_messages', $messages);
        Session::save();
        return $messages;
    }

    public function autocomplete()
    {
        // return latest few messages of customer
        $token = request('token');
        $messages = array();
        $response = $this->getConversation(request('convId'), $token);
        foreach ($this->loadOpenMessages($response, $token) as $msg) {
            array_push($messages, $msg['text']);
        }
        return response()->json(array_slice($messages, 0, 3));
    }

    /* public function loadChat($response, $header)
    {
        $chat = $this->callAPI('GET', $response[0]->otherMediaUris[0], null, $header);
        // dd($chat);
        $customer = $this->getParticipant($chat->participants, 'customer');
        $context = $this->getContext(get_object_vars($customer->attributes));

        $subject = isset($context) ? $context['Product'] : ''; // get chat subject from chat
        return $subject;
    } */

    public function getParticipant($participants, $purpose)
    {
        foreach ($participants as $participant) {
            if ($participant->purpose == $purpose) {
                return $participant;
            }
        }
    }

    /* public function getContext($attributes)
    {
        $custom = "context.customField";

        $customFields = array();
        for ($i = 1; $i <= 3; $i ++) {
            $fields = $this->customField($i);
            $customFields[$attributes[$fields['label']]] = $attributes[$fields['value']];
        }

        return $customFields;
    }

    public function customField($n)
    {
        $custom = "context.customField";
        $label = $custom . $n . "Label";
        $value = $custom . $n;
        return [
            'label' => $label,
            'value' => $value
        ];
    } */

    public function callAPI($method, $url, $body, $headerAdd)
    {
        $env = $this->getSettings("ENVIRONMENT");
        $header = array(
            "Content-type: application/json",
            "Accept: application/json"
        );

        $header += $headerAdd;

        switch ($method) {
            case 'POST':
                $response = Http::withHeaders($header)->post("https://api." . $env . $url, $body);
                break;
            case 'GET':
                $response = Http::withHeaders($header)->get("https://api." . $env . $url);
                break;
        }
        return json_decode($response);
    }

    public function getMessage()
    {
        $fromMessage = request('text');
        return view('homekno7', [
            'from' => $fromMessage,
            'convId' => session('conversationid')
        ]);
    }

    public function index($new)
    {
        $convId = Session::get('conversationid');
        $accessToken = Session::get('access_token');
        $response = $this->getConversation($convId, $accessToken);
        if(isset($response[0]->status) && $response[0]->status == 401 && $response[0]->code == 'bad.credentials'){
            Log::info('Gen validation in index:Code'.$response[0]->code);
            throw new \Exception(Settings::where('section','display')->where('name','ERR_GENESYS_CRED')->first()->value);
        }
        if ($new && isset($response[0]->entities)) {
            $response = $response[0]->entities;
        }
        $subject = $this->getCustomerContext($response, $accessToken);
        Log::info("Index :: CONVID::" . $convId." ACCESSTOKEN::".$accessToken);
        $conv = Conversation::where('conversation_id', $convId)->first();
        $user = $conv->user_id;
        Session::put("user", $user);
        Log::info('User added to genesys session:' . $user);
        // get articles from knosys
         $knosys = new KnosysController();
         $knosys->loadConversationUser($convId);
         $results = $knosys->searchToken($subject, "");
         $results["conversationid"] = $convId;
         if ($results['size'] == 0) {
             return view('homekno7', [
                 'results' => $results
             ]);
         } else {
             return view('homekno7', [
                 'results' => $results
             ])->with('success', 'Showing results for '.$subject);
         }
    }

    public function testView()
    {
        return view('homekno7');
    }
    
    public function returnErrorToView($conv, $errorMsg){
        $results = array();
        $results['size'] = 0;
        $results["conversationid"] = $conv;
        return view('homekno7', [
            'results' => $results,
            'error' => $errorMsg
        ]);
    }
    
    public function returnSuccessToView($conv, $successMsg){
        $results = array();
        $results['size'] = 0;
        $results["conversationid"] = $conv;
        return view('homekno7', [
            'results' => $results,
            'success' => $successMsg
        ]);
    }

    public function getCustomerContext($response, $token)
    {
        $customer = $this->getParticipant($response[0]->participants, 'customer');
        if(!isset($customer->attributes)){
            throw new \Exception("Closed conversation");
        }
        $context = $customer->attributes;
        $subject = '';
        $contextRef = Settings::where('name','customer_context')->first()->value;
        Log::info('Context:'.$contextRef);
        switch($contextRef){
            case 'subject':$subject = $context->customerSubject;
            break;
            case 'product':$subject = $context->customerProduct;
            break;
            default:$subject = $contextRef;
            break;
        }
        return $subject;
    }

    public function login()
    {
        $conversation = Conversation::where('conversation_id', request('conversationid'))->first();
        try {
            if ($conversation == null) {
                return $this->getAuthCode(request('conversationid'));
            } else {
                return $this->loadSessionDetails($conversation);
            }
        } catch (\Throwable $e) {
            return $this->returnErrorToView($conversation->conversation_id, $e->getMessage());
        }
    }

    public function loadSessionDetails($conversation)
    {
        Log::info("Conversation exists");
        $user = GenUser::where('user_id', $conversation->user_id)->first();
        if($user==null){
            Log::info("User details not updated for conv". $conversation->conversation_id);
            Conversation::where('conversation_id', $conversation->conversation_id)->delete();
            header("refresh:0;");
            throw new \Exception("Re-loading conversation");
        } else {
            // check if user access token has expired
            $createdAt = strtotime($user->updated_at);
            $now = strtotime(date('Y-m-d H:i:s'));
            Log::info("Time difference::".($now - $createdAt));
            if (($now - $createdAt) >= $user->expires_in) {
                // request for new access_token using refresh token
                Log::info("Token expired for user:".$user->user_id);
                $user = $this->renewToken($user);
            }
            Session::put('access_token', $user->access_token);
            Session::put('conversationid', $conversation->conversation_id);
        }
        return $this->index(false);
    }

    function renewToken($user)
    {
        $token_url = 'https://login.' . $this->getSettings('ENVIRONMENT') . '/oauth/token';
        
        $response = Http::asForm()->post($token_url, [
            'grant_type' => 'refresh_token',
            'refresh_token' => $user->refresh_token,
            'client_id' => $this->getSettings("CLIENT_IMP_ID"),
            'client_secret' => $this->getSettings("CLIENT_IMP_SECRET")
        ]);
        Log::info("Renewing token...");
        if($response->failed()){
            Log::info("Error renewing token... Initiating re-authentication". $response);
            Conversation::where('conversation_id', request('conversationid'))->delete();
            GenUser::where('user_id', $user->_user_id)->delete();
            header("refresh:0;");
        } else {
            $jsonResponse = json_decode($response);
            Log::info($jsonResponse->access_token!=null?"Successfully renewed token":"Unable to renew token");
            
            //update user details with new refresh token, auth token, expiry 
            $updatedUser = GenUser::where('user_id', $user->user_id)->first();
            $updatedUser->update([
                'access_token' => $jsonResponse->access_token,
                'expires_in' => $jsonResponse->expires_in,
                'refresh_token' => $jsonResponse->refresh_token,
                'updated_at' => strtotime(date('Y-m-d H:i:s')),
                'role' => $this->userIsAdmin($user->user_id, $jsonResponse->access_token)
            ]);
        }
        
        $updatedUser = GenUser::where('user_id', $user->user_id)->first();
        Log::info("User id token renewed = ".$user->user_id);
        return $updatedUser;
    }

    function getAuthCode($conversationId)
    {
        //request for authentication code and redirect to login
        $conversation = Conversation::where('conversation_id', $conversationId)->first();
        $state = Str::random(10);
        if( $conversation == null){
            //store temporary entry for every new conversation with state as user id
            $conversation = Conversation::updateOrCreate([
                'conversation_id' => $conversationId,
                'start_time' => date('Y-m-d H:i:s'),
                'user_id' => $state
            ]);
            $conversation->save();
        }
        Log::info("Dummy conversation entry: " . $conversation->conversation_id." state:".$state);
        
        try {
        $query = http_build_query([
            'client_id' => $this->getSettings("CLIENT_IMP_ID"),
            'redirect_uri' => $this->getSettings("WEBHOOK_URL").'/callback',
            'response_type' => 'code',
            'scope' => '',
            'state' => $state
        ]);
        } catch(\Throwable $e){
            Conversation::where('conversation_id', $conversation->conversation_id)->delete();
            Log::info("Gen validation authcode: ".$e->getMessage());
            throw new \Exception(Settings::where('section','display')->where('name','ERR_GENESYS_CRED')->first()->value);
        }
        return Redirect::to('https://login.' . $this->getSettings("ENVIRONMENT") . '/oauth/authorize?' . $query);
        
    }
    
    function getcodeAccessToken()
    {
        //request auth token using obtained authentication code
        $state = request()->state;
        Log::info("Dummy user/state:".$state);
        $codeVerifier = request()->session()->pull('code_verifier');
        $env = $this->getSettings("ENVIRONMENT");
        $body = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->getSettings("CLIENT_IMP_ID"),
            'client_secret' => $this->getSettings("CLIENT_IMP_SECRET"),
            'redirect_uri' => $this->getSettings("WEBHOOK_URL").'/callback',
            'code_verifier' => $codeVerifier,
            'code' => request()->code
        ];
        $response = Http::asForm()->post('https://login.' . $env . '/oauth/token', $body);
        if($response->failed()){
            Log::info("Gen validation:accstok".$response->reason());
            $error = Settings::where('section','display')->where('name','ERR_GENESYS_CRED')->first()->value;
            return $this->returnErrorToView("invalid", $error);
        }
        $jsonResponse = json_decode($response);
        Session::put('access_token', $jsonResponse->access_token);
        
        $user = $this->updateUser($jsonResponse, request()->code);
        
        
        //get active conversations of user
        //if conversation does not exist? save it and pass the id to session
        $new_conversation = Conversation::where('user_id', $state)->first();
        $convTst = $this->getUserConversations($new_conversation->conversation_id);
        if(isset($convTst->status) && $convTst->status >= 400){
            $error = Settings::where('section','display')->where('name','ERR_INVALID_CONVO')->first()->value;
            Conversation::where('conversation_id', $new_conversation->conversation_id)->delete();
            return $this->returnErrorToView("invalid", $error);
        }
        
        if ($new_conversation != null) {
            Conversation::where('conversation_id', $new_conversation->conversation_id)->update([
                "start_time" => date('Y-m-d H:i:s', strtotime($new_conversation->startTime)),
                "user_id" => $user->id
            ]);
            Log::info("New conversation entered :" . $new_conversation->conversation_id." user id".$user->id);
        }
        if (!isset($new_conversation)) {
            //should not happen
            // log error should not happen -- the given conv id not found for user.
            $error = Settings::where('section','display')->where('name','ERR_INVALID_CONVO')->first()->value;
            Conversation::where('user_id',$state)->delete();
            return $this->returnErrorToView("invalid", $error);
            Log::error("Invalid Conversation ID");
        } else {
            Session::put('conversationid', $new_conversation->conversation_id);
            Log::info("new conv added to session: " . $new_conversation->conversation_id);
        }

        try {
            return $this->index(true);
        } catch (\Throwable $e){
            return $this->returnErrorToView(Session::get('conversationid'), $e->getMessage());
        }
    }
    
    public function updateUser($jsonResponse, $code){
        //get current logged in user
        $userResponse = $this->getCurrentUser();
        if(is_array($userResponse) && isset($userResponse[0]->status)&& $userResponse[0]->status == 401 && $userResponse[0]->code == 'bad.credentials') {
            Log::info('Gen validation: cannot get user:'.$userResponse[0]->code);
            $error = Settings::where('section','display')->where('name','ERR_GENESYS_CRED')->first()->value;
            return $this->returnErrorToView("invalid", $error);
        } else {
            $user = $userResponse;
           
            //generate user if does not exist and save
            GenUser::where('user_id', $user->id)->updateOrCreate(
                ['user_id' => $user->id],
                ['auth_code' => $code,
                    'name' => $user->name,
                    'email' => $user->email,
                    'access_token' => $jsonResponse->access_token,
                    'expires_in' => $jsonResponse->expires_in,
                    'refresh_token' => $jsonResponse->refresh_token,
                    'role' => $this->userIsAdmin($user->id, $jsonResponse->access_token)
                ]);
            
            return $user;
        }
    }
    
    public function getUserConversations($convId){
        $header = ["Authorization"=> "Bearer ".session('access_token')];
        return $this->callAPI('GET', '/api/v2/conversations/'.$convId, null, $header);
    }
    
    public function getCurrentUser(){
        $header = ["Authorization"=> "Bearer ".session('access_token')];
        return $this->callAPI('GET', "/api/v2/users/me", null, $header);
    }
    
    public function userIsAdmin($id, $access_token){
        Log::info("Getting roles for user:".$id);
        $roles = $this->callAPI("GET", "/api/v2/users/".$id."/roles",null, ["Authorization"=> "Bearer ".$access_token])->roles;
        foreach($roles as $role){
            if($role->name == 'admin'){
                return "admin";
            }
        }
        return "";
    }
}


?>
