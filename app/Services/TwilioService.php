<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        
        if (empty($sid) || empty($token)) {
            throw new \Exception('Twilio SID oraaaaaaaaaaaaaaaaaaaaaaaaaaaaS Auth Token is not set in the environment variables.');
        }

        $this->client = new Client($sid, $token);
    }

    public function sendSms($to, $message)
    {
        if ( !$this->isValidPhoneNumber($to)) {
            throw new \Exception($to.'  The recipient phone number is invalid.');
        }

        $this->client->messages->create($to, [
            'from' => getenv("TWILIO_PHONE_NUMBER"),
            'body' => $message,
        ]);
    }
    private function isValidPhoneNumber($number)
    {
        // Regex للتحقق من صيغة الرقم الدولي
        return preg_match('/^\+\d{1,3}\d{4,14}(?:x.+)?$/', $number);
    }
}