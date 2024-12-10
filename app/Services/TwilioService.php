<?php

namespace App\Services;
use Twilio\Rest\Client;

class TwilioService 
{
    protected $client;
    public function __construct()
    {
        $this->client = new Client(env('TWILIO_SID'),env('JWILIO_AUTH_TOKEN'));
    }

    public function sendSMS($to, $message)
    {
        // dd($message, $to,env('TWILIO_SID'),env('TWILIO_PHONE_NUMBER'));
        return $this->client->messages->create($to, [
            'from' => env('TWILIO_PHONE_NUMBER'),
            'body' => "votre code de packing est: ". $message
        ]);
    }
}