<?php

namespace App\Http\Livewire;

use App\Http\Controllers\KnosysController;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Home extends Component
{
    public $results;
    public $quiz;
    public $alerts;
    public $isAdmin;
    public $sessionExpired;

    public function mount($results) {
        $this->results = $results;
    }
    
    public function render()
    {
        $user = DB::table('gen_users')
        ->join('conversations','conversations.user_id','=','gen_users.user_id')
        ->where('conversation_id',$this->results['conversationid'])
        ->select('gen_users.*')
        ->first();
        
        $createdAt = strtotime($user->updated_at);
        $now = strtotime(date('Y-m-d H:i:s'));
        if (($now - $createdAt) >= $user->expires_in) {
            // request for new access_token using refresh token
            $this->sessionExpired = true;
        }
        
        $this->isAdmin = $user != null ? $user->role == 'admin':false;
        return view('livewire.home',['results' => $this->results]);
    }
    
    public function getPageResults($url){
        $knosys = new KnosysController();
        $this->results = $knosys->searchPages($url, $this->results['conversationid']);
        $this->emit('refreshResults'); 
    }

    public function getAlerts(){
        $knosys = new KnosysController();
        $this->alerts = $knosys->getAlerts($this->results['conversationid']);
        $this->dispatchBrowserEvent('openAlerts');
    }

    public function getQuiz(){
        $knosys = new KnosysController();
        $this->quiz = $knosys->getQuiz( $this->results['conversationid']);
        $this->dispatchBrowserEvent('openQuiz');
        
       
       
    }
}
