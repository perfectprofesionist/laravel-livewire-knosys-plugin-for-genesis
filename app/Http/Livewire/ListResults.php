<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Conversation;

class ListResults extends Component
{
    public $result;
    public $item;
    public $settings;
    public $userId;
    public $conversationid;
    public $sections;
    
    protected $listeners = ['refreshResults' => '$refresh'];
    
    public function mount($result) {
        if(!isset($result['search'])){
            $result['search'] = '';
        }
        $this->conversationid = $result['conversationid'];
        $conv = Conversation::where('conversation_id',$result['conversationid'])->first();
        if(isset($result['headers'])){
            $total = explode("/", $result['headers']['Content-Range'][0]);
            $result['headers'] += ['Total'=>$total[1]+ 1];
        }
        if($conv != null){
            $this->userId = $conv->user_id;
        }
        $this->pinned = array(); 
        $this->result = $result;  
    }
    
    public function viewItem($id, $type){
        $item = array_filter($this->result['showResults'], function ($i) use ($id) {
            return ($i['detail']['id'] == $id);
        });
        $this->item = array_values($item)[0];
		
        $this->dispatchBrowserEvent('item-updated', ['item' => $this->item,"conversationid" => $this->result['conversationid']]);
        
    }
    
    public function render()
    {
        return view('livewire.list-results', ['result' => $this->result,
            'item' => $this->item,
            'userId' => $this->userId,
            'conversationid' => $this->conversationid
        ]);
    }
    
    public function resultSort($value) 
    {   
        if ($value == "lh-rel"){       
            usort($this->result['showResults'], function($a, $b) {  return strcmp($a['relevance'], $b['relevance']);});
        }
        else if($value == "hl-rel"){
            usort($this->result['showResults'], function($a, $b) {return -1 *strcmp($a['relevance'], $b['relevance']);}); 
        }
        else if ($value == "date"){           
            usort($this->result['showResults'], function($a, $b) {return -1 *strcmp(strtotime($a['detail']['liveDate']), strtotime($b['detail']['liveDate']));}); 
        }       
    }
}
