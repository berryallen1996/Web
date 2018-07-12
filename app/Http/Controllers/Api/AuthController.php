<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator, DB, Hash, Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

class AuthController extends Controller
{
    //
    /**
     * API Register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $credentials = $request->only('first_name','last_name','email', 'password','contact_no');
        
        $rules = [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'contact_no' => 'required|min:8|max:16|unique:users',
            'password' => 'required|min:8|max:16'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
        	$this->message  = $validator->messages()->first();
            $this->status   = "false";
        }else{
            $insert_data = [
                'first_name'  => $request->first_name,
                'last_name'  => $request->last_name,
                'email' => $request->email,
                'contact_no' => $request->contact_no,
                'type' => 'user',
                'password' => Hash::make($request->password)
            ];
	        
	        $user = User::create($insert_data);
	        $verification_code = $this->randomString(6); //Generate verification code
	        DB::table('user_verifications')->insert(['user_id'=>$user->id,'token'=>$verification_code]);
	        // OTP code
            $this->data = $user;
            $this->data['verification_code'] = $verification_code;

	        $this->message    = 'messages.successful_signup';
        }
        
        return $this->response();
    }

    /**
     * API Verify User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyUser(Request $request)
    {
    	$credentials = $request->only('verification_code');
        
        $rules = [
            'verification_code' => 'required'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
        	$this->message  = $validator->messages();
            $this->status   = "false";
        }else{

            $existUser = User::where('email',$request->email)->first();

            if($existUser){

                $verification_code = $request->verification_code;
                $check = DB::table('user_verifications')->where('token',$verification_code)->first();

                if(!is_null($check)){

                    $user = User::find($check->user_id);
                    
                    if($user->is_verified == 1){
                        
                        $this->message    = 'messages.already_verfied';
                    }else{

                        $user->update(['is_verified' => 1]);
                        DB::table('user_verifications')->where('token',$verification_code)->delete();
                        $this->data = $this->dologin($user->email);
                        // $this->data['token'] = $token;
                        $this->message    = 'messages.login_successfull';
                    }
                    
                }else{
                    
                    $this->message    = 'messages.code_invalid';
                    $this->status   = "false";
                }
            }else{
                $this->message    = 'messages.email_invalid';
                $this->status   = "false";
            }
        	
        }

        return $this->response();
    }

    /**
     * Perform Login into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $result (Data of users)
     * @return \Illuminate\Http\Response
     */
    
    private function dologin($email){

        $user = User::where('email',$email)->first();  

        return $user;
    }

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
        	$this->message    = $validator->messages();
        	$this->status   = "false";
        }
        else{
        	$credentials['is_verified'] = 1;
	        try {
	            // attempt to verify the credentials and create a token for the user
	            if (! $token = JWTAuth::attempt($credentials)) {
	            	$this->message    = 'messages.incorrect_email_password';
	            }else{
                    $this->message    = 'messages.login_successfull';
                    $this->data = $this->dologin($request->email);
                    $this->data['token'] = $token;
                }
	        } catch (JWTException $e) {
	        	$this->status   = "false";
                $this->message    = 'messages.failed_login';
                return $this->response();
            }
	        
		}
        return $this->response();
    }


    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request) {
    	dd($request->all());
        $this->validate($request, ['token' => 'required']);
        
        try {
            JWTAuth::invalidate($request->input('token'));
           	$this->message    = 'messages.logout';
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            $this->status   = "false";
	        $this->message    = 'messages.failed_logout';
        }
        return $this->response();
    }
}
