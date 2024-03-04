<?php

namespace App\Http\Livewire;

use App\Models\Settings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Http\Controllers\KnosysController;
use function PHPUnit\Framework\throwException;
use App\Models\GenUser;

class SettingsLive extends Component
{
    //display
    public $LBL_NO_RES;
    public $ERR_RESPONSE_TIMEOUT;
    public $ERR_SAVE;
    public $ERR_SNIPPET_COPY;
    public $ERR_DOC_SEND;
    public $ERR_INVALID_CONVO;
    public $ERR_KNOSYS_CRED;
    public $ERR_GENESYS_CRED;
    public $SCCS_SAVED;
    public $SCCS_SNIPPET_COPY;
    public $SCCS_DOC_SEND;
    public $SCCS_RVW_SAVED;
    public $SCCS_MSG_SENT;
    public $ERR_RATING_EMPTY;
    public $SCCS_EMAIL_SENT;
    public $ERR_NO_RES;
    public $ERR_FEEDBACK_EMPTY;
    public $ERR_MSG_NOT_SENT;
    public $ERR_MAIL_NOT_SENT;
    //general
    public $WEBHOOK_URL;
    public $ENVIRONMENT;
    public $CLIENT_IMP_ID;
    public $CLIENT_IMP_SECRET;
    public $pageinate;
    //user mapping
    public $customer_context;    
    //knowledger iq
    public $KNO_ENV;
    public $KNO_AUTH_ENV;
    public $KNO_AUTH_TOKEN;
    public $KNO_SITE_ID;
    public $KNO_SECRET;
    public $KNO_USER_TYPE;
    
    public $LoadedSetting = array();
    public $convId;
    
    protected $rules = [
        'pageinate' => 'required|numeric|min:1|max:255',
        'customer_context' => 'required|regex:/^[a-zA-Z\s]*$/|max:255'
    ];
    
    public function render()
    {
        return view('livewire.settings-live',['LoadedSetting'=>$this->LoadedSetting]);
    }
    
    public function mount($convId){
        $this->convId = $convId;
         foreach(['display','user','general','kiq'] as $section){
            $this->LoadedSetting += [$section => array()];
            foreach(Settings::where('section',$section)->get() as $set){
                $this->LoadedSetting[$section]+=[$set['name']=>$set];
            }
        }
        $this->loadSettings("display");
        $this->loadSettings("general");
        $this->loadSettings("user");
        $this->loadSettings("kiq");
    }
    
    public function loadSettings($form){
        
        switch($form){
            case 'display':
                //display
                $this->LBL_NO_RES=$this->LoadedSetting['display']['LBL_NO_RES']['value'];
                $this->ERR_RESPONSE_TIMEOUT=$this->LoadedSetting['display']['ERR_RESPONSE_TIMEOUT']['value'];
                $this->ERR_SAVE=$this->LoadedSetting['display']['ERR_SAVE']['value'];
                $this->ERR_SNIPPET_COPY=$this->LoadedSetting['display']['ERR_SNIPPET_COPY']['value'];
                $this->ERR_DOC_SEND=$this->LoadedSetting['display']['ERR_DOC_SEND']['value'];
                $this->ERR_INVALID_CONVO=$this->LoadedSetting['display']['ERR_INVALID_CONVO']['value'];
                $this->ERR_KNOSYS_CRED=$this->LoadedSetting['display']['ERR_KNOSYS_CRED']['value'];
                $this->ERR_GENESYS_CRED=$this->LoadedSetting['display']['ERR_GENESYS_CRED']['value'];
                $this->SCCS_SAVED=$this->LoadedSetting['display']['SCCS_SAVED']['value'];
                $this->SCCS_SNIPPET_COPY=$this->LoadedSetting['display']['SCCS_SNIPPET_COPY']['value'];
                $this->SCCS_DOC_SEND=$this->LoadedSetting['display']['SCCS_DOC_SEND']['value'];
                $this->SCCS_RVW_SAVED=$this->LoadedSetting['display']['SCCS_RVW_SAVED']['value'];
                $this->SCCS_MSG_SENT=$this->LoadedSetting['display']['SCCS_MSG_SENT']['value'];
                $this->ERR_RATING_EMPTY=$this->LoadedSetting['display']['ERR_RATING_EMPTY']['value'];
                $this->SCCS_EMAIL_SENT=$this->LoadedSetting['display']['SCCS_EMAIL_SENT']['value'];
                $this->ERR_NO_RES=$this->LoadedSetting['display']['ERR_NO_RES']['value'];
                $this->ERR_FEEDBACK_EMPTY=$this->LoadedSetting['display']['ERR_FEEDBACK_EMPTY']['value'];
                $this->ERR_MSG_NOT_SENT=$this->LoadedSetting['display']['ERR_MSG_NOT_SENT']['value'];
                $this->ERR_MAIL_NOT_SENT=$this->LoadedSetting['display']['ERR_MAIL_NOT_SENT']['value'];
                break;
            case 'general':
                //general
                $this->WEBHOOK_URL = $this->LoadedSetting['general']['WEBHOOK_URL']['value'];
                $this->ENVIRONMENT = $this->LoadedSetting['general']['ENVIRONMENT']['value'];
                $this->CLIENT_IMP_ID = $this->LoadedSetting['general']['CLIENT_IMP_ID']['value'];
                $this->CLIENT_IMP_SECRET = $this->LoadedSetting['general']['CLIENT_IMP_SECRET']['value'];
                $this->pageinate = $this->LoadedSetting['general']['pageinate']['value'];
                break;
            case 'user':
                //user
                $this->customer_context = $this->LoadedSetting['user']['customer_context']['value'];
                break;
            case 'kiq':
                //knowledger iq
                $this->KNO_ENV = $this->LoadedSetting['kiq']['KNO_ENV']['value'];
                $this->KNO_AUTH_ENV = $this->LoadedSetting['kiq']['KNO_AUTH_ENV']['value'];
                $this->KNO_AUTH_TOKEN = $this->LoadedSetting['kiq']['KNO_AUTH_TOKEN']['value'];
                $this->KNO_SITE_ID = $this->LoadedSetting['kiq']['KNO_SITE_ID']['value'];
                $this->KNO_SECRET = $this->LoadedSetting['kiq']['KNO_SECRET']['value'];
                $this->KNO_USER_TYPE = $this->LoadedSetting['kiq']['KNO_USER_TYPE']['value'];
                break;
        }
    }
    
    public function update_env($data,$form)
    {
        $kno = new KnosysController();
        if($form == 'kiq'){
            //check credentials for genesys
            $header = array(
                "Content-type" => "application/json",
                "Accept" => "*/*"
                
            );
            $challenge_url = '/auth/challenge';
            $req_body = [
                "accessToken" => $this->KNO_AUTH_TOKEN,
                "siteId" =>  $this->KNO_SITE_ID,
                "userType" =>  $this->KNO_USER_TYPE
            ];
            try {
                $resp = Http::timeout(60)->withHeaders($header)->post($this->KNO_AUTH_ENV . $challenge_url, $req_body);
                $challenge = json_decode($resp)->challengeString;
            } catch(\Throwable $e){
                return [1,$this->LoadedSetting['display']['ERR_KNOSYS_CRED']['value']];
            }
            
            
            try {
                $secret = hex2bin( $this->KNO_SECRET);
                $signature = hash_hmac('sha256', $challenge, $secret, true);
                $signature = base64_encode($signature);
                
                $token_url = '/auth/token';
                
                $req_body = [
                    'challenge' => $challenge,
                    'signature' => $signature
                ];
                Log::info("Challenge req:" . implode(",", $req_body));
                $token = $kno->callAPI('POST', $token_url, $req_body, null, 2);
                
                $searchUrl = "/agent/search/?q=test";
                
                $kno->loadConversationUser($this->convId);
                $AuthHeader = $kno->loadToken();
                $header += $AuthHeader;
                $data = Http::timeout(60)->withHeaders($header)->get($this->KNO_ENV . $searchUrl);
                if($data->failed()){
                    throw new \Exception("Invalid");
                }
            } catch(\Throwable $e){
                return [1,$this->LoadedSetting['display']['ERR_KNOSYS_CRED']['value']];
            }
            
        } else if ($form == 'user'){
            //validate input for custom context
        } else if ($form == 'general'){
           
        }
        return [0,"No errors"];
        
    }
    
    public function setenv($form)
    {
        $data = $this;
        $error = $this->update_env($data,$form);
        if($error[0]==1){
            
            session()->flash('error',$error[1]);
        } else {
            $this->validate();
            foreach($data as $key => $value){
                if($value == null){
                    continue;
                }
                try{
                    Settings::where('name', $key)
                    ->where('section',$form)
                    ->update(['value'=>$value]);
                } catch(\Illuminate\Database\QueryException $ex){
                    return [1,$this->LoadedSetting['display']['ERR_SAVE']['value']];
                    break;
                }
            }
            session()->flash('success',$this->LoadedSetting['display']['SCCS_SAVED']['value']);
        }
        foreach(Settings::where('section',$form)->get() as $set){
            $this->LoadedSetting[$form][$set['name']]=$set;
        }
        $this->loadSettings($form);
    }
}
