<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ProcessDocument extends Component
{
    public $document;
    public $review;
    public $userId;
    public $stars;
    public $from;
    
    public function render()
    {
        return view('livewire.process-document', ['document'=>$this->document]);
    }
    
    protected $rules = [
        'stars' => 'required|max:5',
        'review' => 'required|max:250'
    ];
    
    public function mount($document, $userId, $from) {
        $detail = $document["detail"];
        $detail["liveDate"] = date("d-m-Y", strtotime($detail["liveDate"]));
        $document["detail"] = $detail;
        $this->document = $document;
        $this->userId = $userId;
        $this->from = $from;
    }
}
