<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Article;
use App\Models\DocumentFile;
use App\Models\RelatedLink;
use App\Models\Link;
use App\Models\AccordionParagraph;
use App\Models\Settings;
use App\Models\Document;
use App\Models\Conversation;
use App\Models\KnoUser;
use Carbon\Carbon;
use function GuzzleHttp\json_encode;
use Illuminate\Http\Client\ConnectionException;
use App\Models\GenUser;
use Illuminate\Support\Str;

class KnosysController extends Controller
{

    // Knosys authentication
    
    public $access_token;
    
    public $settings;
    
    public function getSettings($name){
        if($this->settings == null){
            $this->settings = array();
            foreach(Settings::where('section','kiq')->get() as $set){
                $this->settings+=[$set['name']=>$set];
            }
        }
        return $this->settings[$name]->value;
    }

    public function getChallenge()
    {
        // step 1
        $challenge_url = '/auth/challenge';
        Log::info("Generating session");
        $req_body = [
            "accessToken" => $this->getSettings("KNO_AUTH_TOKEN"),
            "siteId" =>  $this->getSettings("KNO_SITE_ID"),
            "userType" =>  $this->getSettings("KNO_USER_TYPE")
        ];

        return $this->callAPI('POST', $challenge_url, $req_body, null, 1)->challengeString;
    }

    public function getAccessToken()
    {
        // step 2
        $challenge = $this->getChallenge();
        if (! isset($challenge)) {
            // throw error challenge not generated
        }

        $secret = hex2bin( $this->getSettings("KNO_SECRET"));
        $signature = hash_hmac('sha256', $challenge, $secret, true);
        $signature = base64_encode($signature);

        $token_url = '/auth/token';

        $req_body = [
            'challenge' => $challenge,
            'signature' => $signature
        ];
        Log::info("Challenge req:" . implode(",", $req_body));
        return $this->callAPI('POST', $token_url, $req_body, null, 2);
    }

    public function login()
    {
        $user = Session::get('user');
        $knoUser = KnoUser::where('user_id', $user)->first();
        if ($knoUser == null || $this->hasTokenExpired($knoUser->access_expires_in)) {
            // if knosys user does not exist yet, create from scratch
            Log::info("Kno user does not exist, loading token... ");
            $access_token = $this->getAccessToken(); // should be an authenticate public token
                                                     // dd($access_token);
            $impersonate = $this->impersonate($access_token);
            if(!KnoUser::where('user_id',$user)->exists()){
                $knoUser = KnoUser::updateOrCreate([
                    'user_id' => $user,
                    'access_token' => $access_token->token,
                    'access_expires_in' => strtotime($access_token->expiration),
                    'public_token' => $impersonate->token,
                    'public_access_expires_in' => strtotime($impersonate->expiration)
                ]);
                $knoUser->save();
            } else {
                KnoUser::where('user_id',$user)->update([
                    'access_token' => $access_token->token,
                    'access_expires_in' => strtotime($access_token->expiration),
                    'public_token' => $impersonate->token,
                    'public_access_expires_in' => strtotime($impersonate->expiration)
                ]);
            }
            
            session([
                'knosys_token' => $impersonate->token
            ]);
        } else if ($this->hasTokenExpired($knoUser->public_access_expires_in)) {
            Log::info("Public token expired... renewing...");
            $impersonate = $this->impersonate($knoUser->access_token);
            KnoUser::where('user_id', $user)->update([
                'public_token' => $impersonate->token,
                'public_access_expires_in' => $impersonate->expires_in,
                'public_token_created_at' => date('Y-m-d H:i:s')
            ]);
            session([
                'knosys_token' => $impersonate->token
            ]);
        }

        session([
            'knosys_token' => $knoUser->public_token
        ]);
    }

    public function impersonate($access_token)
    {
        // step 3
        $impersonate_url = '/auth/impersonate';
        $body = [
            'personID' => null,
            'username' => 'knoadmin'
        ];
        $header = [
            "Authorization" => "Bearer " . $access_token->token
        ];

        return $this->callAPI('POST', $impersonate_url, $body, $header, 3);
    }

    public function hasTokenExpired($expires_in)
    {
        $now = strtotime(date('Y-m-d H:i:s'));
        Log::info("Time difference::" . ($now - $expires_in));
        // check if token expired.
        return $now >= $expires_in;
    }
    
    public function loadConversationUser($conversationid){
		//echo $conversationid;
        $conv = Conversation::where('conversation_id', $conversationid)->first();
		//echo "<br ";print_r($conv->user_id);echo "/>";
        if (isset($conv->user_id)) {
            $userId = $conv->user_id;
            $user = GenUser::where('user_id',$userId)->first();
            Session::put([
                "user" => $userId,
                "access_token" => $user->access_token
            ]);
            Log::info('User added to knosys session:' . $userId);
            Session::put("conversationid", $conv->conversation_id);
        }else{
            $error = Settings::where('section','display')->where('name','ERR_INVALID_CONVO')->first()->value;
            echo "<h1>". $error."</h1>";
            die;
        }
    }

    public function callAPI($method, $url, $body, $headerAdd, $n)
    {
        $header = array(
            "Content-type" => "application/json",
            "Accept" => "*/*"

        );
        if($headerAdd != null){
            $header += $headerAdd;
        }
        $env_url = $n <= 3 ?  $this->getSettings("KNO_AUTH_ENV") :  $this->getSettings("KNO_ENV");
        try {
            switch ($method) {
                case 'POST':
                    try{
                       $response = Http::timeout(60)->withHeaders($header)->post($env_url . $url, $body);
                       // print_r($response->status());
                       // print_r($response->headers());
                       // print_r($response->body());
                    }catch(\Exception $e){
                        session()->flash('error','Something went wrong.');
                    }
                    break; 
                case 'PUT':
                    try{
                        $response = Http::timeout(60)->withHeaders($header)->put($env_url . $url, $body);
                        if($response->status() == "204" || $response->status() == 204){
                             session()->flash('success','Rating submitted successfully.');
                        }else{
                             session()->flash('error','Something went wrong.');
                        }
                    }catch(\Exception $e){
                         session()->flash('error','Something went wrong.');
                    }
                    break;
                case 'GET':
                    $response = Http::timeout(60)->withHeaders($header)->get($env_url . $url);
                    if(!is_array($response->body()))
                       // die();
                    if(str_contains($url, '/agent/links/')){
                        if($response->status() == "404" || $response->status() == 404){
                            return json_decode($response->body());
                        }
                    }
                    if(str_contains($url, '/agent/processwizards/')){
                        if($response->status() == "404" || $response->status() == 404){
                            return json_decode($response->body());
                        }
                    }
                    if(str_contains($url, '/resources/files/')){
                        if($response->status() == "200" || $response->status() == 200){
                            return $response->body();
                        }
                    }
                    break;

            }
        } catch (ConnectionException $e) {
            throw new \Exception($e->getMessage());
        } catch (\Throwable $e){
            throw new \Exception($e->getMessage());
        }
        if ($response->failed()) {
            throw new \Exception("API call failed.");
            return view('homekno7', [
                'results' => null
            ]);
            
        }
        if ($n == 4) {
            //dd($response->headers()); // request
            // dd(json_decode($response));//response
            // dd(json_last_error_msg());
        }
        $data = json_decode($response);
        if($method != "PUT"){
            $data->headers = $response->headers();
        }
        return $data;
    }
    
    public function loadToken()
    {
        if (session('knosys_token') == null) {
            $this->login();
        }
        return [
            "Authorization" => "Bearer " . session('knosys_token')
        ];
    }
    
    // Knosys resource handling
    
    public function getResource($resourceType, $id, $ext, $contentType, $AuthHeader){
        $header = array(
            "Content-type" => $contentType,
            "Accept" => "*/*"
        );
        $header += $AuthHeader;
        $url =  $this->getSettings("KNO_ENV").'/resources/'.$resourceType."s/".$id.$ext;
        try {
            $response = Http::timeout(60)->withHeaders($header)->get($url);
        } catch (ConnectionException $e) {
            return view('homekno7', [
                'results' => null
            ]);
        }
        return base64_encode($response);
    }

    public function search()
    {
        ini_set('memory_limit', '64M');
       // $this->getwizardResponses();
        /* if (request()->conversationid == null) {
            // testing only
            $access_token = $this->getAccessToken(); // should be an authenticate public token
                                                     // dd($access_token);
            $impersonate = $this->impersonate($access_token);
            session([
                'knosys_token' => $impersonate->token
            ]);
        } else */ 
        
        $this->loadConversationUser(request()->conversationid);
        
        $validator = Validator::make(request()->all(), [
            'gsearch' => 'nullable',
        ]);
        
        if($validator->fails()){
            $msg = implode(",", array_values($validator->errors()->getMessages())[0]);
            $msg = str_replace('gsearch', 'keyword', $msg);
            $results = array();
            $results['size'] = 0;
            $results["conversationid"] = request()->conversationid;
            return view('homekno7', [
                'results' => $results
            ])->with('error',$msg);;
        }
        
        $search = urldecode(request('gsearch'));
        
        if(session("tab")==null) {
            
        $results = $this->searchToken($search);
        $results["conversationid"] = request()->conversationid;
        }
        //dd($results);
        if($search == null){
            return view('homekno7', [
                'results' => $results
            ]);
        }
        if ($results['size'] == 0 && $search != null) {
            return view('homekno7', [
                'results' => $results
            ])->with('error', Settings::where('section','display')->where('name','ERR_NO_RES')->first()->value);
        } else {
            return view('homekno7', [
                'results' => $results
            ])->with('success', 'Showing results for '.$search);
        }
    }
    
    public function searchToken($token)
    {
        // authentication
        $header = $this->loadToken();
       // Log::info("Header:" . implode(", ", $header)); 
        
        $resLimit = Settings::where('name','pageinate')->first()->value;;
        
        if($token!= null){            
            // search directly from knosys based on keyword
            $url = '/agent/search/advanced?q=' . $token.'&limit='.$resLimit;
            $response = $this->callAPI('GET', $url, null, $header, 4);
           }                                  
        if (!isset($response->values) || $response->values == null ) {
            Log::info("No results for the searched token ".$token);
            $results['size'] = 0;
            $results['search'] = $token;
            return $results;
        }
        $results = $this->parseResults($response, $header);      
        $results['size'] = sizeof($response->values); 
        $results['headers'] = $response->headers;
        $results['search'] = $token;
        
        return $results;
    }
    
    public function searchPages($url,$convId){
        // authentication
        $token = explode("&",explode("=", $url)[1])[0];
        $this->loadConversationUser($convId);
        $header = $this->loadToken();
        Log::info("Header:" . implode(", ", $header));
        
        if($url!= null){
            // search directly from knosys based on keyword
            $header += array(
                "Content-type" => "application/json",
                "Accept" => "*/*"
            );
            
            $response = Http::timeout(60)->withHeaders($header)->get($url);
            $data = json_decode($response);
            $data->headers = $response->headers();
        }
        if (!isset($data->values) || $data->values == null ) {
            $results['size'] = 0;
            return $results;
        }
        request()->merge(["conversationid"=>$convId]);
        $results = $this->parseResults($data, $header);
        $results["conversationid"] = $convId;
        $results['size'] = sizeof($data->values);
        $results['headers'] = $data->headers;
        $results['search'] = $token;
        return $results;
    }
    
    public function getFile($file,$fields)
    {
       $fileVar = $this->getResource(
            $file["detail"]["itemType"], 
            $file["detail"]["id"], 
            $fields["FileName_FileExtension"], 
            $fields["FileName_MimeType"],
            $this->loadToken()
        );
       Storage::disk('public')->put('files/'.$file["detail"]["id"].$fields["FileName_FileExtension"],base64_decode($fileVar));
        return $fileVar;
    }
    public function parseResults($response, $header)
    {
        $results = array();        
        $showResults= array();
        $Files = array();
        $documentFiles = array();
        $relatedLinks = array();
        $Links = array();
        $accordionParagraphs = array();       
        $processflows = array();
        foreach ($response->values as $val) {
            if(!isset($val->id)){
                continue;
            }
            $item = $this->callAPI('GET', str_replace($this->getSettings("KNO_ENV"), "", $val->url), null, $header, 5);
            $item->isPinned = $val->showInPinned;
            //parse process flows

            if($val->itemType=='ProcessFlowExternal'){
                array_push($processflows, $this->getProcessFlow($item, $header));
            } else {
                $fields = $this->getFields($item);
                $item = json_decode(json_encode($item), true);
                $item["fields"] = $fields;
                $item["relevance"] = $val->relevance;
            }

            switch ($val->itemType) {
                case 'NewsArticle':
                    array_push($showResults, $this->getArticle($item,$fields, $val->id));
                    break;
                case 'DocumentFile':
                    array_push($documentFiles, $this->getDocumentFile($item));
                    break;
                case 'File':
                    
                    $item['File'] = $this->getFile($item,$fields);
                    array_push($Files, $item);
                    
                    break;
                case 'RelatedLink':
                    array_push($relatedLinks, $this->getRelatedLink($item));
                    break;
                case 'Link':
                    array_push($Links, $this->getLink($item));
                    break;
                case 'AccordionParagraph':
                    array_push($accordionParagraphs, $this->getAccordionParagraph($item));
                    break;
                case 'Document':
                    $doc = $this->getDocument($item, $val->id);
                    $doc["relevance"] = round($val->relevance);
                    array_push($showResults, $doc);
                    break;
            }
        }
        $results['showResults'] = $showResults;
        $results['documentFiles'] = $documentFiles;
        $results['Files'] = $Files;
        $results['relatedLinks'] = $relatedLinks;
        $results['Links'] = $Links;       
        $results['processflows'] = $processflows;
        return $results;
    }
    
    public function getProcessFlow($item, $headerAdd){
        $header = array(
            "Content-type" => "image/svg+xml",
            "Accept" => "*/*"
        );
        $header += $headerAdd;
       
        $item->Image = Http::timeout(60)->withHeaders($header)->get($item->imageUrl);
        $item =get_object_vars($item); 
        $item['Image'] = preg_replace("/<?xml [^?>]+\>/i", "", $item['Image']);  
        return $item;
       
    }

    public function getArticle($object, $fields, $id)
    {
        $object["image"] = $this->getArticleImage($fields["ImageItemGuid"]);
        $object["feedback"] = $this->getArticleFeedback($id);
        return new Article($object);
    }

    public function getDocumentFile($object)
    {
        return new DocumentFile($object);
    }

    public function getRelatedLink($object)
    {
        return new RelatedLink($object);
    }

    public function getLink($object)
    {
        return new Link($object);
    }

    public function getAccordionParagraph($object)
    {
        return new AccordionParagraph($object);
    }

    public function getDocument($object, $id)
    {
        $document = $this->getDocChild($object);
        $document["feedback"] = $this->getArticleFeedback($id);
       /*  //for testing
        if($id=="78bf9b66-5cd5-eb11-a839-000d3ae08db5"){
            $document["isPinned"] = true;
        } */
        return new Document($document);
    }
    
    public function getDocChild($object){
        $children = $object["children"];
        $set = 0;
        $childrenWithFields = array();
        foreach($children as $child){
            $fields=$this->getFields($child);
            $child["fields"] = $fields;
            
            if($child["children"] != null){
                $child = $this->getDocChild($child);
            }
            switch($child["detail"]["itemType"]){
                case "DocumentFile":
                    $child["docFile"] = $this->getResource(
                        $child["detail"]["itemType"], 
                        $child["detail"]["id"], 
                        $fields["DocumentFileName_FileExtension"], 
                        $fields["DocumentFileName_MimeType"],
                        $this->loadToken()
                    );
                    Storage::disk('public')->put('files/'.$child["detail"]["id"]. $fields["DocumentFileName_FileExtension"],base64_decode($child["docFile"]));
                    array_push($childrenWithFields,$child);
                
                break;
                case "File":
                    $child['File'] = $this->getFile($child, $fields);
                    array_push($childrenWithFields,$child);
                break;
                case "AudioFile":
                    $child["audioFile"] = $this->getResource(
                        $child["detail"]["itemType"], 
                        $child["detail"]["id"], 
                        $fields["AudioFileName_FileExtension"], 
                        $fields["AudioFileName_MimeType"],
                        $this->loadToken()
                    );
                    Storage::disk('public')->put('files/'.$fields["AudioFileName_OriginalFilename"],base64_decode($child["audioFile"]));
                    array_push($childrenWithFields,$child);
                
                break;
                case "VideoFile":
                    $child["videoFile"] = $this->getResource(
                        $child["detail"]["itemType"], 
                        $child["detail"]["id"], 
                        $fields["VideoFileName_FileExtension"], 
                        $fields["VideoFileName_MimeType"],
                        $this->loadToken()
                    );
                    Storage::disk('public')->put('files/'.$fields["VideoFileName_OriginalFilename"],base64_decode($child["videoFile"]));
                    array_push($childrenWithFields,$child);
                
                break;
                
                case "Icon":
                    $set=3;
                    $child["Icon"] = $this->getResource(
                        $child["detail"]["itemType"],
                        $child["detail"]["id"],
                        $fields["IconFile_FileExtension"],
                        $fields["IconFile_MimeType"],
                        $this->loadToken()
                    );
                    array_push($childrenWithFields,$child);
                break;
                
                case "RelatedLinks":
                    array_push($childrenWithFields,$child);
                    break;
                
                case "Image":$child["fields"]["ImageSrc"]=$this->getResource(
                        $child["detail"]["itemType"],
                        $child["detail"]["id"],
                        $fields["ImageFile_FileExtension"],
                        $fields["ImageFile_MimeType"],
                        $this->loadToken()
                    );
                array_push($childrenWithFields,$child);
                break;
                    
                case "AccordionParagraph":
                    $myvalue = $child["fields"]["AccordionList"];  
                    $myvalueCont = $child["fields"]["AccordionContent"];       
                    $accList = explode("\r\n",$myvalue);
                    $accCont = explode("<hr class=\"accordionsplit\">",$myvalueCont);
                    $child["fields"]["AccordionList"] = $accList; 
                    $child["fields"]["AccordionContent"] = $accCont; 
                    array_push($childrenWithFields,$child);                    
                  
                    break;
                case "ProcessFlowExternal":
                    $flow = $this->callAPI('GET', '/agent/processflows/'.$child["detail"]["id"], null, $this->loadToken(), 15);
                    $child["processFlow"] = $this->getProcessFlow($flow, $this->loadToken());
                    $child['conversationid'] = Session::get('conversationid');
                    array_push($childrenWithFields,$child);
                    break;
                    
                default:array_push($childrenWithFields,$child);
            }
            
        }
        $object["children"] = $childrenWithFields;
        if($set==3){
            //dd($object);
        }
        return $object;
    }
    
    public function getArticleById($articleId)
    {
        $this->loadConversationUser(request()->conversationid);
        $header = $this->loadToken();
        $url = "/agent/newsarticles/" . $articleId;
        $article = $this->callAPI('GET', $url, null, $header, 6);
        $fields = $this->getFields($article);
        
        $article = json_decode(json_encode($article), true);
        $article["fields"] = $fields;
        // get knosys tags of article
        return $this->getArticle($article,$fields,$articleId);
    }
    
    public function getDocumentById($docId, $convId)
    {
        $this->loadConversationUser($convId);
        $header = $this->loadToken();
         Log::info("Header:" . implode(", ", $header)); 
        $url = "/agent/documents/" . $docId;
        Log::info("getDocumentById url " . $url);
        $document = $this->callAPI('GET', $url, null, $header, 10);
        $fields = $this->getFields($document);
        $document = json_decode(json_encode($document), true);
        $document["fields"] = $fields;
        Log::info("getDocumentById response id " . $docId, ['response'=>$document]);
        return $this->getDocument($document,$docId);
    }
    
    public function getProcessFlowById($flowId,$convId)
    {
        $this->loadConversationUser($convId);
        $header = $this->loadToken();
         Log::info("Header:" . implode(", ", $header)); 
        $url = "/agent/processflows/" . $flowId;
         Log::info("getProcessFlowById url " . $url);
        $flow = $this->callAPI('GET', $url, null, $header, 10);
        Log::info("getProcessFlowById response id " . $flowId, ['response'=>$flow]);
        return $this->getProcessFlow($flow, $header);
    }

    public function getDocumentFiles($docId)
    {   
        $this->loadConversationUser(request()->conversationid);
        $header = $this->loadToken();
        $url = "/agent/documentfiles/" . $docId;
        $docs = $this->callAPI('GET', $url, null, $header, 9);
        $docs = json_decode(json_encode($docs), true);
       
        $fields = $this->getFields($docs);

        $docs["fields"] = $fields;
        $docs["fields"]["docFile"] = $this->getResource(
            $docs["detail"]["itemType"],
            $docs["detail"]["id"],
            $fields["DocumentFileName_FileExtension"],
            $fields["DocumentFileName_MimeType"],
            $header
        );
        Log::info("Fetching Document " . $docId);
        return $docs;
    }

    public function getArticleImage($imageId)
    {
        $this->loadConversationUser(request()->conversationid);
        $header = $this->loadToken();
        $url = "/agent/images/" . $imageId;
        $image = $this->callAPI('GET', $url, null, $header, 7);
        $image = json_decode(json_encode($image), true);
        $fields = $this->getFields($image);
        $image["fields"] = $fields;
        $image["fields"]["ImageSrc"] = $this->getResource(
            $image["detail"]["itemType"],
            $image["detail"]["id"],
            $fields["ImageFile_FileExtension"],
            $fields["ImageFile_MimeType"],
            $header
        );
        Log::info("Fetching image " . $imageId);
        return $image;
    }
    
    public function getLinkRes($linkId){
        $this->loadConversationUser(request()->conversationid);
        $header = $this->loadToken();
        $url = "/agent/links/". $linkId;
        $link = $this->callAPI('GET', $url, null, $header, 8);
        $link = json_decode(json_encode($link), true);
        if(!isset($link["fields"])){
            $link["fields"]["URL"] = '';
        } else {
            $fields = $this->getFields($link);
            $link["fields"] = $fields;
        }
        return $link;
    }

    public function getDocumentRes($linkId){
        $this->loadConversationUser(request()->conversationid);
        $header = $this->loadToken();
        //$url = "/agent/files/". $linkId;
        //$link = $this->callAPI('GET', $url, null, $header, 8);

        $url = "/resources/files/". $linkId;

        $link = $this->callAPI('GET', $url, null, $header, 8);
        file_put_contents('../public/documentfiles/Customer Enquiry - Order follow-up V1.oft', $link);
       // $link = json_decode(json_encode(["DocumentFile"=>$link]), true);
        // if(!isset($link["fields"])){
        //     $link["fields"]["URL"] = '';
        // } else {
        //     $fields = $this->getFields($link);
        //     $link["fields"] = $fields;
        // }
        return $link;
    }


    public function getFields($response)
    {
        $fields = array();
        if(gettype($response)=="array"){
            foreach ($response["fields"] as $field) {
                if($field["name"] == "Date"){
                    $field["value"] = Carbon::parse($field["value"])->diffForHumans(Carbon::now());
                }
                $fields[$field["name"]] = $field["value"];
            }
        } else {
            foreach ($response->fields as $field) {
                if($field->name == "Date"){
                    $field->value = Carbon::parse($field->value)->diffForHumans(Carbon::now());
                }
                $fields[$field->name] = $field->value;
            }
        }
        return $fields;
    }
    
    //Utilities functions
    
    public static function getArticleFeedback($articleId){
        $feedOfArticle = DB::table('feedback')
        ->join('gen_users', 'feedback.user_id', '=', 'gen_users.user_id')
        ->where('article_id',$articleId)
        ->select('feedback.*', 'gen_users.name')
        ->orderBy('created_at','desc')
        ->get();
        
        $feedback = [
            "average" => round($feedOfArticle->average('rating_star')),
            "count" => sizeof($feedOfArticle)!=0? sizeof($feedOfArticle):0,
            "review" => $feedOfArticle
        ];
        
        $feedback["review"] =  json_decode(json_encode($feedback["review"]), true);
        
        return $feedback;
    }

    public function getSections($conversationid){
        $this->loadConversationUser($conversationid);
        $header = $this->loadToken();
        $url = "/agent/feedback/sections";
        $sections = $this->callAPI("GET", $url, null, $header, 6);
       return $sections;
    }
    public function submitknosysRating($conversationid, $itemId,$title,$content,$method,$type,$sectionId){
       
        $this->loadConversationUser($conversationid);
        $header = $this->loadToken();
        if($method == "PUT"){
            $url = "/agent/documents/".$itemId."/rating";
            if($type=="2"){
                 $url = "/agent/newsarticles/".$itemId."/rating";
            }
            $req_body = [
                "rating"=>$content
            ];
        }else{
            $url = "/agent/documents/".$itemId."/feedback/current";
            if($type=="2"){
                $url = "/agent/newsarticles/".$itemId."/feedback/current";
            }
            $uuid = Str::uuid()->toString();
            $req_body = [
                "sectionId"=> $sectionId,
                "feedbackType"=> 2,
                "feedbackTitle"=>"$title",
                "feedbackText"=>"$content",
               // "responseText"=> "$content",
                "submit"=> true
            ];
            $this->callAPI($method, $url, $req_body, $header, 6);
        }
    }

    public function getAlerts($conversationid){
        
        $this->loadConversationUser($conversationid);
        $header = $this->loadToken();

        $url = "/agent/alerts/";
        $alerts = $this->callAPI('GET', $url, null, $header, 6);                            
        $alerts = json_decode(json_encode($alerts), true);
        //echo "<pre>";print_r($alerts);       
        return $alerts;
    }

    public function getQuiz($conversationid){        
        $this->loadConversationUser($conversationid);
        $header = $this->loadToken();
        $url = "/agent/quizzes/";
        $quiz = $this->callAPI('GET', $url, null, $header, 6);
        $quiz = json_decode(json_encode($quiz), true);
        $frontURL = str_replace('rest', 'agent', $this->getSettings("KNO_ENV"));
        $frontURL = str_replace('/api/v2', '', $frontURL);
        $updatedQuizzes = array();
        foreach($quiz['values'] as $q){
            $q['url'] = $frontURL.'/quiz/'.$q['quizId'];
            array_push($updatedQuizzes,$q);
        }
        $quiz['values'] = $updatedQuizzes;
        return $quiz;
    }

    //get response from wizard api for process wizards
    public function getwizardResponses($processId,$itemId,$conversationid){
        $this->loadConversationUser($conversationid);
        $header = $this->loadToken();

        $req_body = [
                "processId"=>$processId,
                "itemId"=> $itemId,
            ];
        $url = "/agent/processwizards/responses"; 
        $response = $this->callAPI("POST", $url, $req_body, $header, 6);
        if(isset($response->newId)){
            $url = "/agent/processwizards/responses/".$response->newId; 
            $getWizard = $this->callAPI("GET", $url, null, $header, 6);
            $wizardbyId['wizard'] =  json_decode(json_encode($getWizard), true);
            $wizardbyId['responseId'] =  $response->newId;
            return $wizardbyId;
        }
    }

    //get response from wizard api for process wizards
    public function submitAnswer($conversationid,$responseId,$selectedRelationshipId,$notes){
        $this->loadConversationUser($conversationid);
        $header = $this->loadToken();
        $req_body = [
                "selectedRelationshipId"=>"$selectedRelationshipId",
                "selectedAnswerOptionIds"=> ["fe2f8433-1548-ec11-a83f-000d3ae106ec","ff2f8433-1548-ec11-a83f-000d3ae106ec"],
                "notes"=> $notes,
            ];
        $url = "/agent/processwizards/responses/".$responseId."/advance"; 
        $response = $this->callAPI("POST", $url, $req_body, $header, 6);
        $url = "/agent/processwizards/responses/".$responseId; 
        $getWizard = $this->callAPI("GET", $url, null, $header, 6);
        $wizardbyId['wizard'] =  json_decode(json_encode($getWizard), true);
        $wizardbyId['responseId'] =  $responseId;
        //echo "<pre>";print_r($wizardbyId);
        return $wizardbyId;
    }

    //submit second step from child
    public function submitSecondStep($conversationid,$responseId,$selectedRelationshipId,$notes){
        $this->loadConversationUser($conversationid);
        $IdsArr = explode(",",$selectedRelationshipId);
        $newArr = [];
        foreach($IdsArr as $ids){
            array_push($newArr,$ids); 
        }
        $header = $this->loadToken();
        $req_body = [
                "selectedRelationshipId"=>"c0f821e9-1748-ec11-a83f-000d3ae106ec",
                "selectedAnswerOptionIds"=> $newArr,
                "notes"=> $notes,
            ];
        $url = "/agent/processwizards/responses/".$responseId."/advance"; 
        $response = $this->callAPI("POST", $url, $req_body, $header, 6);
        $url = "/agent/processwizards/responses/".$responseId; 
        $getWizard = $this->callAPI("GET", $url, null, $header, 6);
        $wizardbyId['wizard'] =  json_decode(json_encode($getWizard), true);
        $wizardbyId['responseId'] =  $responseId;
        //echo "<pre>";print_r($wizardbyId);
        return $wizardbyId;
    }

    //submit third step from child
    public function submitThirdStep($conversationid,$responseId,$selectedRelationshipId,$notes){
        $this->loadConversationUser($conversationid);
        $header = $this->loadToken();
        $req_body = [
                "selectedRelationshipId"=>$selectedRelationshipId,
                "selectedAnswerOptionIds"=> [],
                "notes"=> $notes,
            ];
        $url = "/agent/processwizards/responses/".$responseId."/advance"; 
        $response = $this->callAPI("POST", $url, $req_body, $header, 6);
        $url = "/agent/processwizards/responses/".$responseId; 
        $getWizard = $this->callAPI("GET", $url, null, $header, 6);
        $wizardbyId['wizard'] =  json_decode(json_encode($getWizard), true);
        $wizardbyId['responseId'] =  $responseId;
        //echo "<pre>";print_r($wizardbyId);
        return $wizardbyId;
    }

    
}