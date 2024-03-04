<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Http\Controllers\KnosysController;
use App\Models\Conversation;


class ProcessFlow extends Component
{
    public $processflow;
    public $convId;
    public $userId;
    public $item;
    public $result;
    
    protected $listeners = [
        'process-click' => 'showArticle',
    ];
    
    public function mount($result){
        $this->result = $result;
        $this->convId = $this->result['conversationid'];
    }
    
    public function viewProcess($id){
        $item = array_filter($this->result['processflows'], function ($i) use ($id) {
            return ($i['processFlowId'] == $id);
        });
        $this->processflow = array_values($item)[0];
        $this->dispatchBrowserEvent('flow-updated', ['processflow' => $this->processflow]);
        
    }
    
    public function showArticle($url){
        $kno = new KnosysController();
        $item = explode('/', $url);
        switch($item[0]){
            case 'Document': $this->item = $kno->getDocumentById($item[1],$this->convId);
            $this->userId = Conversation::where('conversation_id',$this->convId)->first()->user_id;
            break;
            
            case 'ProcessFlowExternal': $this->item = $kno->getProcessFlowById($item[1],$this->convId);
            break;
        }
        Log::info($url);
        $this->dispatchBrowserEvent('flow-item');
    }
    
    public function render()
    {
        return view('livewire.process-flow',['result'=>$this->result, 'processflow'=>$this->processflow, 'item'=>$this->item]);
    }
}
