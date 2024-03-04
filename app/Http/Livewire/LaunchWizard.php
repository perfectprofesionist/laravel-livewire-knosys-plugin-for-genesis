<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Http\Controllers\KnosysController;
use App\Models\Conversation;

class LaunchWizard extends Component
{
    public $wizardflow;
    public $convId;
    public $itemid;
    public $userId;
    public $responseId;
    public $selectedRelationshipId;
    public $notes;
    public $item;
    
    protected $listeners = [
        'process-click-doc' => 'showArticle',
    ];
    
    public function render()
    {
        return view('livewire.launch-wizard');
    }
    
    public function mount($wizardflow){
        $this->convId = "02317d2b-3de0-4da4-8c2c-a71d7bf857bf";
    }
    
    //Function for opening process wizard setup
    public function viewProcessWizard($processId,$itemId,$convId){
        $kno = new KnosysController();
        $this->processwizard = $kno->getwizardResponses($processId,$itemId,$convId);
        $this->dispatchBrowserEvent('process-wizard', [$this->processwizard]);
    }

    //function for process wizard answer submission
    public function submitAnswer($convId,$responseId,$selectedRelationshipId,$notes){
        $kno = new KnosysController();
        $this->processwizard = $kno->submitAnswer($convId,$responseId,$selectedRelationshipId,$notes);
        $this->dispatchBrowserEvent('process-wizard', [$this->processwizard]);
    }
    //function for process wizard answer submission
    public function submitSecondStep($convId,$responseId,$selectedRelationshipId,$notes){
        $kno = new KnosysController();
        $this->processwizard = $kno->submitSecondStep($convId,$responseId,$selectedRelationshipId,$notes);
        $this->dispatchBrowserEvent('process-wizard', [$this->processwizard]);
    }

    //function for process wizard answer submission
    public function submitThirdStep($convId,$responseId,$selectedRelationshipId,$notes){
        $kno = new KnosysController();
        $this->processwizard = $kno->submitThirdStep($convId,$responseId,$selectedRelationshipId,$notes);
        $this->dispatchBrowserEvent('process-wizard', [$this->processwizard]);
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
        $this->dispatchBrowserEvent('flow-item-doc');
    }
}
