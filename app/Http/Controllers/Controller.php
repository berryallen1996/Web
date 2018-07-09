<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $message = '';
	protected $status= "true";
	protected $error_code ='';
	protected $errors =[];
	protected $data= [];
	protected $sizePerPage = 10;

	function __construct(Request $request) {

		$this->sizePerPage = $request->has('sizePerPage') ?  $request->get('sizePerPage') : 10;
	}

	/**
	* This function uses for sending to the client.
	* @param  integer $code [status code]
	* @return \Illuminate\Http\Response
	*/
	protected function response($code=200)
	{
		
		$data = [
		'message'=>trans($this->message),
		'status'=>$this->status,
		'error_code'=>$this->error_code,
		'errors'=>$this->errors,
		'data'=>$this->data,
		];

		return response()->json($data, $code);
	}

	function mailSender($email, $subject, $data, $template, $type = 'send') {
		if($type == 'send'){
			return Mail::send($template, $data, function ($message) use($email, $subject, $data){
		        $message->from('donotreply@dishdash.co', $data['site_name']);
		        $message->to($email)->subject($subject);
		    });
		}else{
			return Mail::queue($template, $data, function ($message) use($email, $subject, $data){
		        $message->from('donotreply@dishdash.co', $data['site_name']);
		        $message->to($email)->subject($subject);
		    });
		}
	}

	function randomString($length = 10){
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
	}

	function emailSettings() {
	    return array(
	        'site_name'         => env('PROJ_NAME'),
	        'slogan'            => env('PROJ_NAME'),
	        'site_link'         => env('ASSET_URL'),
	        'help_email'        => env('MAIL_USERNAME'),
	    );
	}
}
