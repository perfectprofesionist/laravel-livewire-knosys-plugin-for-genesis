<?php

namespace App\Http\Livewire;

use App\Models\Feedback;
use App\Models\Settings;
use Livewire\Component;
use App\Http\Controllers\KnosysController;

class ArticleView extends Component
{
    public $article;
    public $review;
    public $userId;
    public $title;
    public $sectionid;
    public $sectionmain;
    public $stars;
    public $conversationid;
    
    protected $rules = [
        'stars' => 'required|max:5',
        'review' => 'required|max:250',
        'title' => 'required|max:50'
    ];
    
    public function mount($article, $userId,$conversationid) {
        $this->article = $article;
        $this->userId = $userId;
        $this->conversationid = $conversationid;
    }
    
    public function render()
    {
        $KnosysController = new KnosysController();
        $sections = $KnosysController->getSections($this->conversationid);
        return view('livewire.article-view',['article'=>$this->article,'sections'=>$sections]);
    }
    
    public function submitFeedback(){
        $conversationid = $this->conversationid;
        if(!isset($this->review) && !isset($this->title)){
            session()->flash('error', Settings::where('section','display')->where('name','ERR_FEEDBACK_EMPTY')->first()->value);
        } else {
            if(trim($this->review) == "" || trim($this->title) == "") {  
                session()->flash('error', Settings::where('section','display')->where('name','ERR_FEEDBACK_EMPTY')->first()->value);
            }else{
                $articleId = $this->article['detail']['id'];
                $feedback = Feedback::create([
                    'article_id' => $articleId,
                    'user_id' => $this->userId,
                    'rating_star' => !isset($this->stars)?0:$this->stars,
                    'rating_text' => !isset($this->review)?'':$this->review,
                    
                ]);
                $feedback->save();
                $review =  !isset($this->review)?'':$this->review;
                $sectionid =  !isset($this->sectionid)?'':$this->sectionid;
                $title =  !isset($this->title)?'':$this->title;
                unset($this->stars);
                unset($this->review);
                unset($this->title);
                unset($this->sectionid);
                unset($this->sectionmain);
                
                request()->merge(["created_at"=>date('F j, Y, g:i a')]);
                $KnosysController = new KnosysController();
                $KnosysController->submitknosysRating($conversationid, $articleId,$title,$review,"POST","2",$sectionid);
                $this->article["feedback"] = KnosysController::getArticleFeedback($articleId);
                session()->flash('success', Settings::where('section','display')->where('name','SCCS_RVW_SAVED')->first()->value);
            }
        }
    }

    public function submitRating(){
        $conversationid = $this->conversationid;
        if(!isset($this->stars) && !isset($this->review)){
            session()->flash('error', Settings::where('section','display')->where('name','ERR_RATING_EMPTY')->first()->value);
        } else {
            $articleId = $this->article['detail']['id'];
            $feedback = Feedback::create([
                'article_id' => $articleId,
                'user_id' => $this->userId,
                'rating_star' => !isset($this->stars)?0:$this->stars,
                'rating_text' => !isset($this->review)?'':$this->review,
            ]);
            $feedback->save();
            $stars =  !isset($this->stars)?0:$this->stars;
            unset($this->stars);
            unset($this->review);
            $KnosysController = new KnosysController();
            $KnosysController->submitknosysRating($conversationid, $articleId,null,$stars,"PUT","2",'');
            
            request()->merge(["created_at"=>date('F j, Y, g:i a')]);
            $this->article["feedback"] = KnosysController::getArticleFeedback($articleId);
            session()->flash('success', Settings::where('section','display')->where('name','SCCS_RVW_SAVED')->first()->value);
        }
    }
}
