<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function store()
    {
        Log::info("User submitting feedback::".request()->user_id);
        $genesys = new GenesysController();
        $feedback = Feedback::updateOrCreate([
            'article_id' => request()->article_id,
            'user_id' => request()->user_id,
            'rating_star' => request()->stars,
            'rating_text' => request()->review,
           
        ]);
        $feedback->save();
        request()->merge(["created_at"=>date('F j, Y, g:i a')]);
        return request();
    }
 
}
