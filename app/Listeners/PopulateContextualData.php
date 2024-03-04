<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ReceivedRequest;
use App\Http\Controllers\GenesysController;

class PopulateContextualData
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ReceivedRequest $event)
    {
        $gen = new GenesysController();
        $convId = $event->conversationId;
        $url = "/api/v2/conversations/{$convId}";
        
        $env = $gen->getSettings("ENVIRONMENT");
        $header = array(
            "Content-type: application/json",
            "Accept: application/json",
            "Authorization: Bearer ".$event->access_token
        );
        
        $curl = curl_init();
        $options = array(
            CURLOPT_URL => "https://api.{$env}".$url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true
            );
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
}
