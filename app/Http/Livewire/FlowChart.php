<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Http\Controllers\KnosysController;

class FlowChart extends Component
{
    public $processflow;
    public $convId;
    
    public function render()
    {
        return view('livewire.flow-chart', ['processflow' => $this->processflow]);
    }
    
    public function mount($processflow,$convId) {
        $this->processflow = $processflow;
        $this->convId = $convId;
    }
    
   
}
