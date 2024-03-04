<?php

namespace App\Http\Livewire;

use App\Models\Settings;
use Livewire\Component;

class Related extends Component
{
    public $result;
    
    protected $listeners = [
        'copy-text' => 'alertCopy',
        'send-link-to-chat' => 'alertSend',
        'send-email' => 'alertEmail'
    ];
    
    public function render()
    {
        return view('livewire.related',['result' => $this->result]);
    }
    
    public function mount($result) {
        $this->result = $result;
    }
    
    public function alertCopy($msg){
        if($msg!='success'){
            session()->flash('error',Settings::where('section','display')->where('name','ERR_SNIPPET_COPY')->first()->value);
        } else {
            session()->flash('success',Settings::where('section','display')->where('name','SCCS_SNIPPET_COPY')->first()->value);
        }
    }
    
    public function alertSend($msg) {
        if($msg!='success'){
            session()->flash('error',Settings::where('section','display')->where('name','ERR_MSG_NOT_SENT')->first()->value);
        } else {
            session()->flash('success',Settings::where('section','display')->where('name','SCCS_MSG_SENT')->first()->value);
        }
    }
    
    public function alertEmail($msg) {
        // if($msg ==='success'){
            session()->flash('success',Settings::where('section','display')->where('name','SCCS_EMAIL_SENT')->first()->value);
        // } else {
        //    session()->flash('error',Settings::where('section','display')->where('name','ERR_MAIL_NOT_SENT')->first()->value);
        // }

    }
}
