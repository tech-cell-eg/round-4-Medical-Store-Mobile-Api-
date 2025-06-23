<?php

namespace App\Services;

use Twilio\Rest\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );

        $this->from = config('services.twilio.from');
    }


    public function sendSms(string $to, string $message): array
    {
        try {
            $message = $this->client->messages->create(
                $to,
                [
                    'from' => $this->from,
                    'body' => $message
                ]
            );

            return [
                'success' => true,
                'message_sid' => $message->sid,
                'status' => $message->status
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }


    public function sendOtp(string $phoneNumber, string $otpCode): array
    {
        $message = "Your verification code is: {$otpCode}. This code will expire in 5 minutes.";

        return $this->sendSms($phoneNumber, $message);
    }


    public static function generateOtp(): string
    {
        return str_pad(random_int(0, pow(10, 6) - 1), 6, '0', STR_PAD_LEFT);
    }
}
