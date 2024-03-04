<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Http\Controllers\KnosysController;
use App\Models\Conversation;

class LaunchProcess extends Component
{
    public $processflow;
    public $flow;
    
    public $convId;
    public $userId;
    public $item;
    
    protected $listeners = [
        'process-click-doc' => 'showArticle',
    ];
    
    public function render()
    {
        return view('livewire.launch-process',['processflow'=>$this->processflow,'flow'=> $this->flow]);
    }
    
    public function mount($flow){
        $this->flow = $flow;
        $this->convId = $flow['conversationid'];
    }
    
    public function viewProcess($id){
        if($id==$this->flow['processFlow']['processFlowId']){
            $this->processflow = $this->flow['processFlow'];
        }
        $this->dispatchBrowserEvent('process-section', ['processflow' => $this->processflow]);
    }
    
    public function showArticle($url){
        $kno = new KnosysController();
        $item = explode('/', $url);
        switch($item[0]){
            case 'Document': $this->item = $kno->getDocumentById($item[1],$this->convId);
            $this->userId = Conversation::where('conversation_id',$this->convId)->first()->user_id;
            break;
            
            case 'ProcessFlowExternal': $this->item = $kno->getProcessFlowById($item[1],$this->convId);
            $this->item['detail']['itemType']="ProcessFlowExternal";
            break;
        }
        Log::info($url);
        $this->dispatchBrowserEvent('process-section');
    }
}
