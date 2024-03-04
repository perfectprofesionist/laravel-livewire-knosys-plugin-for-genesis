<?php

namespace App\Http\Livewire;

use App\Http\Controllers\KnosysController;
use App\Models\Feedback;
use App\Models\Settings;
use Livewire\Component;

class DocumentView extends Component
{
    public $document;
    public $review;
    public $title;
    public $userId;
    public $stars;
    public $from;
    public $sectionid;
    public $sectionmain;
    public $conversationid;
    
    protected $rules = [
        'stars' => 'required|max:5',
        'review' => 'required|max:250',
        'title' => 'required|max:50'
    ];
    
    protected $listeners = [
        'copy-data' => 'alertCopy',
        'send-to-chat' => 'alertSend',
    ];
    
    public function mount($document, $userId, $from, $conversationid) {
        $detail = $document["detail"];
        $detail["liveDate"] = date("d-m-Y", strtotime($detail["liveDate"])); 
        $document["detail"] = $detail;
        $this->document = $document;
        $this->userId = $userId;
        $this->from = $from;
        $this->conversationid = $conversationid;
    }
    
    public function submitFeedback(){
        $conversationid = $this->conversationid;
        if(!isset($this->review) && !isset($this->title)){
            session()->flash('error', Settings::where('section','display')->where('name','ERR_FEEDBACK_EMPTY')->first()->value);
        } else {
            if(trim($this->review) == "" || trim($this->title) == "") {  
                session()->flash('error', Settings::where('section','display')->where('name','ERR_FEEDBACK_EMPTY')->first()->value);
            }else{
                $documentId = $this->document['detail']['id'];
                $feedback = Feedback::create([
                    'article_id' => $documentId,
                    'user_id' => $this->userId,
                    'rating_star' => !isset($this->stars)?0:$this->stars,
                    'rating_text' => !isset($this->review)?'':$this->review,
                    
                ]);
                $feedback->save();
                $review =  !isset($this->review)?'':$this->review;
                $title =  !isset($this->title)?'':$this->title;
                $sectionid =  !isset($this->sectionid)?'':$this->sectionid;
                unset($this->stars);
                unset($this->review);
                unset($this->title);
                unset($this->sectionid);
                unset($this->sectionmain);
                request()->merge(["created_at"=>date('F j, Y, g:i a')]);
                $KnosysController = new KnosysController();
                $KnosysController->submitknosysRating($conversationid, $documentId,$title,$review,"POST","1",$sectionid);
                $this->document["feedback"] = KnosysController::getArticleFeedback($documentId);
                session()->flash('success', Settings::where('section','display')->where('name','SCCS_RVW_SAVED')->first()->value);
            }
        }
    }

    public function submitRating(){
        $conversationid = $this->conversationid;
        if(!isset($this->stars) && !isset($this->review)){
            session()->flash('error', Settings::where('section','display')->where('name','ERR_RATING_EMPTY')->first()->value);
        } else {
            $documentId = $this->document['detail']['id'];
            $feedback = Feedback::create([
                'article_id' => $documentId,
                'user_id' => $this->userId,
                'rating_star' => !isset($this->stars)?0:$this->stars,
                'rating_text' => !isset($this->review)?'':$this->review,
            ]);
            $feedback->save();
            $stars =  !isset($this->stars)?0:$this->stars;
            unset($this->stars);
            unset($this->review);
            $KnosysController = new KnosysController();
            $KnosysController->submitknosysRating($conversationid, $documentId,null,$stars,"PUT","1",'');
            request()->merge(["created_at"=>date('F j, Y, g:i a')]);
            $this->document["feedback"] = KnosysController::getArticleFeedback($documentId);
            session()->flash('success', Settings::where('section','display')->where('name','SCCS_RVW_SAVED')->first()->value);
        }
    }
    
    public function alertCopy($msg){
        if($msg!='success'){
            session()->flash('error',Settings::where('section','display')->where('name','ERR_SNIPPET_COPY')->first()->value);
        } else {
            session()->flash('success',Settings::where('section','display')->where('name','SCCS_SNIPPET_COPY')->first()->value);
        }
    }
    
    public function render()
    {
        $KnosysController = new KnosysController();
        $sections = $KnosysController->getSections($this->conversationid);
        return view('livewire.document-view',['document'=>$this->document,'sections'=>$sections, 'conversationid' => $this->conversationid]);
    }
    
    public function alertSend($msg) {
        if($msg!='success'){
            session()->flash('error',Settings::where('section','display')->where('name','ERR_MSG_NOT_SENT')->first()->value);
        } else {
            session()->flash('success',Settings::where('section','display')->where('name','SCCS_MSG_SENT')->first()->value);
        }
    }
}
