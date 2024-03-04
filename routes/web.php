<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GenesysController;
use App\Http\Controllers\KnosysController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\FeedbackController;

if(App::environment('production')){
    URL::forceScheme('https');
}

Auth::routes(['register' => false]);

//Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::get('/test', [App\Http\Controllers\GenesysController::class, 'testView'])->name('home');

Route::post('/post_chat', [GenesysController::class, 'sendMessage'])->name('send');
Route::post('/api/messageFromGenesys', [GenesysController::class, 'getMessage'])->name('receive');
Route::get('autocomplete', [GenesysController::class, 'autocomplete'])->name('autocomplete');
Route::get('/test',[GenesysController::class, 'testView']);
Route::get('/', [GenesysController::class,'login'])->name('redirect');
Route::get('/callback', [GenesysController::class,'getCodeAccessToken']);

Route::get('/kno',[KnosysController::class, 'search']);
Route::post('/tag', [KnosysController::class,'addTag'])->name('tag-resolve');
Route::get('/article/{articleId}',[KnosysController::class, 'getArticleById']);
Route::get('/document/{documentId}',[KnosysController::class, 'getDocument']);
Route::get('/images/{imageId}',[KnosysController::class, 'getArticleImage']);
Route::get('/links/{linkId}',[KnosysController::class, 'getLinkRes']);
Route::get('/processwizard/{linkId}',[KnosysController::class, 'getprocessWizardById']);

Route::get('/documentfiles/{linkId}',[KnosysController::class, 'getDocumentRes']);

Route::post('send-email', [SendEmailController::class, 'index']);

Route::post('/test/feedback',[FeedbackController::class, 'store']);

