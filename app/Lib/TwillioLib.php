<?php 
namespace App\Lib; 
use DB;
use Twilio;

class TwillioLib
{

	public $twilio;

	function __construct(){
        // $accountId = env('TWILIO_ACCOUNT_SID');
        // $token = env('TWILIO_AUTH_TOKEN');
        // $fromNumber = env('TWILIO_NUMBER');

        // $this->twilio = new Aloha\Twilio\Twilio($accountId, $token, $fromNumber);
	}

	function sendSms()
	{
        Twilio::message('9696031782', 'HIII');
    }
}