<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\PasswordReset;
use App\UserAddress;
use App\Restaurant;
use App\Dish;
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
            $this->status   = "true";
	        $this->message    = 'messages.successful_signup';
        }
        
        return $this->response();
    }

    public function resend_otp(Request $request)
    {
        $credentials = $request->only('email');
        
        $rules = [
            'email' => 'required|email'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $this->message  = $validator->messages()->first();
        }else{
            $existUser = User::where('email',$request->email)->first();

            if($existUser){
                $verification_code = $this->randomString(6);
                DB::table('user_verifications')->where('user_id',$existUser->id)->delete();
                DB::table('user_verifications')->insert(['user_id'=>$existUser->id,'token'=>$verification_code]);
                // OTP code
                $this->data = $existUser;
                $this->data['verification_code'] = $verification_code;
                $this->status   = "true";
                $this->message    = 'messages.successful_resend';
            }else{
                $this->message    = 'messages.email_invalid';
            }
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
    	$credentials = $request->only('verification_code','email');
        
        $rules = [
            'verification_code' => 'required',
            'email' => 'required|email'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
        	$this->message  = $validator->messages()->first();
        }else{

            $existUser = User::where('email',$request->email)->first();

            if($existUser){

                $verification_code = $request->verification_code;
                $check = DB::table('user_verifications')
                        ->where('token',$verification_code)
                        ->where('user_id',$existUser->id)
                        ->first();

                if(!is_null($check)){

                    $user = User::find($check->user_id);
                    
                    if($user->is_verified == 1){
                        // $user = User::first();
                        $this->message    = 'messages.already_verfied';
                    }else{

                        $user->update(['is_verified' => 1]);
                        
                        DB::table('user_verifications')
                        ->where('token',$verification_code)
                        ->where('user_id',$check->user_id)
                        ->delete();

                        $token = JWTAuth::fromUser($user);
                        $this->data = $this->dologin($user->email);
                        $this->data['token'] = $token;
                        $this->status   = "true";
                        $this->message    = 'messages.login_successfull';
                    }
                    
                }else{
                    
                    $this->message    = 'messages.code_invalid';
                }
            }else{
                $this->message    = 'messages.email_invalid';
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
        	$this->message    = $validator->messages()->first();
        }
        else{
        	$credentials['is_verified'] = 1;
	        try {
	            // attempt to verify the credentials and create a token for the user
	            if (! $token = JWTAuth::attempt($credentials)) {
	            	$this->message    = 'messages.incorrect_email_password';
	            }else{
                    $this->status   = "true";
                    $this->message    = 'messages.login_successfull';
                    $this->data = $this->dologin($request->email);
                    $this->data['token'] = $token;
                }
	        } catch (JWTException $e) {
                $this->message    = 'messages.failed_login';
                return $this->response();
            }
	        
		}
        return $this->response();
    }
    public function send(Request $request) {
        __sendOTP("9696031782","777777");
    }

    /**
     * API Recover Password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgot(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $this->message  = 'messages.email_invalid';
            
        }else{
            $email = $request->email;
            $token = md5(uniqid());
                    
            PasswordReset::where('email', '=', $email)->delete();
            PasswordReset::create(['email' => $email, 'token' => $token]);
            $subject             = 'Your Password Reset Link';
            $email_data          = $this->emailSettings();
            $email_data['token'] = $token;
            $email_data['name']  = ucfirst($user->first_name);

            try{
                $isMailSent = $this->mailSender($request->email,$subject,$email_data,'email.reset_password');
                $this->status   = "true";
                $this->message  = 'messages.reset_email_password';

            }catch(\Expection $e){
                $this->message    = 'messages.email_sending_failed';
            }
            

        }
        return $this->response();
    }
    /**
    * Method for handle Forgot password link
    * @param \Illuminate\Http\Request  $request
    * @param $token
    * @return \Illuminate\Http\Response
    */

    public function resetPassword(Request $request,$token=null)
    {
        $email = PasswordReset::where('token', '=', $token)->first(['email']);
        $email = isset($email)? $email->email : '';
        $user = User::where('email', '=', $email)->first(['id']);
        if(!isset($user->id)){
            return view('errors.customerror', ['message' => 'Incorrect or expired Link.']);
        }

        $id = $user->id;
        return view('forgot_password', ['password_token' => $token, 'id' => $id]);
    }

    /**
    * Method for handle request of Forgot password link
    * @param \Illuminate\Http\Request  $request
    * @param $token
    * @return \Illuminate\Http\Response
    */

    public function updatePassword(Request $request,$token=null)
    {
        $validator = \Validator::make($request->all(),[
                'password' => 'required|min:8|max:32',
                'password_confirmation' => 'required|min:8|max:32',
            ]);

        if($validator->fails()){

            $message  = $validator->messages()->first();
            return view('errors.customerror', ['message' => $message]);
        }else{

            $status = $request->password === $request->password_confirmation;
            $password_token =  PasswordReset::where('token', $token)->orderBy('created_at')->first();

            if($password_token){
                $dateToday = date('Y-m-d H:i:s');
                $diff = round((strtotime($dateToday)-strtotime($password_token->created_at))/60,2);
                if($diff > 120){
                    $password_token->delete();
                    return view('errors.customerror', ['message' => 'Link Expired.']);
                }else if($status){

                    User::where('id',$request->_id)->update(['password' => Hash::make($request->password)]);

                    PasswordReset::where('token', $token)->delete();
                    return view('errors.customerror', ['message' => 'Password has been changed.']);

                }
                else{
                    return view('errors.customerror', ['message' => 'Passwords does not match.']);
                }
            }
            else{
                return view('customerror', ['message' => 'Token Expired.']);      
            }
        }
    }


    /**
     * Dropdown Values
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dropdown(Request $request)
    {
        $credentials = $request->only('type');
        $rules = [
            'type' => 'required|in:country,state,city,locality',
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $this->message    = $validator->messages()->first();
        }
        else{

            $data = [];
            $country_id = isset($request->country_id) ? $request->country_id : null;
            $state_id = isset($request->state_id) ? $request->state_id : null;
            $city_id = isset($request->city_id) ? $request->city_id : null;
            $locality_id = isset($request->locality_id) ? $request->locality_id : null;

            if($request->type == 'country'){
                $data = DB::table('country')->select('id_country as id', 'country_name as name')->get();
            }elseif($request->type == 'state'){
                $data = DB::table('states')->select('id', 'states as name')
                    ->where('country_id',$country_id)
                    ->get();
                
            }elseif($request->type == 'city'){
                $data = DB::table('city')->select('id', 'name')
                    ->where('state_id',$state_id)
                    ->get();
                
            }elseif($request->type == 'locality'){
                $data = DB::table('locality')->select('id', 'name')
                    ->where('city_id',$city_id)
                    ->get();
            }

            $this->data = $data;
            $this->status   = "true";
            $this->message  = 'messages.data_get';
        }
        return $this->response();
    }


    /**
     * Dropdown Values
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAddress(Request $request)
    {
        $credentials = $request->only('token','address','contact_no','state_id','city_id','locality_id','pincode');
        $rules = [
            'token' => 'required',
            'address' => 'required',
            'contact_no' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'locality_id' => 'required',
            'pincode' => 'required'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $this->message    = $validator->messages()->first();
        }
        else{
            $user = $this->getAuthUser($request->token);  
            if($user){
                $insert_data = [
                    'user_id'  => $user->id,
                    'address'  => $request->address,
                    'contact_no'  => $request->contact_no,
                    'country_id'  => '101',
                    'state_id'  => $request->state_id,
                    'city_id' => $request->city_id,
                    'contact_no' => $request->contact_no,
                    'locality_id' => $request->locality_id,
                    'pincode' => $request->pincode
                ];
                
                $userAddress = UserAddress::create($insert_data);
                // $this->data = $userAddress;
                $this->status   = "true";
                $this->message  = 'messages.address_saved';
            }else{
                $this->message  = 'Token Expired Or Invalid';
            }
        }
        return $this->response();
    }

    /**
     * Dropdown Values
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addressList(Request $request)
    {
        $credentials = $request->only('token');
        $rules = [
            'token' => 'required'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $this->message    = $validator->messages()->first();
        }
        else{
            $user = $this->getAuthUser($request->token);   
            if($user){
                $userAddress = UserAddress::where('user_id',$user->id)
                                ->join('users','users.id', '=', 'user_addresses.user_id')
                                ->join('country','country.id_country', '=', 'user_addresses.country_id')
                                ->join('states','states.id', '=', 'user_addresses.state_id')
                                ->join('city','city.id', '=', 'user_addresses.city_id')
                                ->join('locality','locality.id', '=', 'user_addresses.locality_id')
                                ->select('users.first_name',
                                        'user_addresses.address',
                                        'user_addresses.contact_no',
                                        'country.country_name',
                                        'states.states as state_name',
                                        'city.name as city_name',
                                        'locality.name as locality_name',
                                        'user_addresses.pincode')
                                ->get();  
                $this->data = $userAddress;
                $this->status   = "true";
                $this->message  = 'messages.data_get';
            }else{
                $this->message  = 'Token Expired Or Invalid';
            }
        }
        return $this->response();
    }

    public function changePassword(Request $request){
            
        $credentials = $request->only('old_password','new_password','confirm_password');
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required'
        ];
        $validator = Validator::make($credentials, $rules);
        
        if($validator->fails()) {
            $this->message    = $validator->messages()->first();
        }
        else{
            $user = $this->getAuthUser($request->token);   
            if($user){
                User::change($user->id,[
                    'password'      => Hash::make($request->new_password),
                    'updated_at'    => date('Y-m-d H:i:s')
                ]);
                $this->status   = true;
                $this->message  = 'messages.password_changed_successfully';
            }else{
                $this->message  = 'Token Expired Or Invalid';
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

    /**
     * Restaurant List
     * 
     *
     * @param Request $request
     */
    public function restaurant(Request $request) {
        
        $state_id = isset($request->state_id) ? $request->state_id : null;
        $city_id = isset($request->city_id) ? $request->city_id : null;
        $locality_id = isset($request->locality_id) ? $request->locality_id : null;
        $pincode = isset($request->pincode) ? $request->pincode : null;
        $address = isset($request->address) ? $request->address : null;
        
        $data = Restaurant::leftjoin('country','country.id_country', '=', 'restaurant.country_id')
                        ->leftjoin('states','states.id', '=', 'restaurant.state_id')
                        ->leftjoin('city','city.id', '=', 'restaurant.city_id')
                        ->leftjoin('locality','locality.id', '=', 'restaurant.locality_id')
                        ->select('restaurant.id',
                                'restaurant.name',
                                'restaurant.address',
                                'restaurant.contact_no',
                                'country.country_name',
                                'states.states as state_name',
                                'city.name as city_name',
                                'locality.name as locality_name',
                                'restaurant.pincode',
                                DB::raw("ifnull(restaurant.image,'') as image"))
                        ->where('restaurant.state_id',$state_id)
                        ->where('restaurant.city_id',$city_id)
                        ->where('restaurant.locality_id',$locality_id)
                        ->where('restaurant.pincode',$pincode)
                        // ->where('address',$address)
                        ->get();
        $this->data = $data;
        $this->status   = "true";
        $this->message  = 'messages.data_get';
        return $this->response();
    }


    /**
     * Restaurant List
     * 
     *
     * @param Request $request
     */
    public function dishes(Request $request) {
        $credentials = $request->only('restaurant_id');
        $rules = [
            'restaurant_id' => 'required'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $this->message    = $validator->messages()->first();
        }
        else{

        }
        $restaurant_id = isset($request->restaurant_id) ? $request->restaurant_id : null;
        $name = isset($request->name) ? $request->name : null;
        $category = isset($request->category) ? $request->category : null;
        
        $data = Dish::join('dish_restaurant','dish_restaurant.dish_id', '=', 'dishes.id')
                        ->join('restaurant','restaurant.id', '=', 'dish_restaurant.restaurant_id')
                        ->leftjoin('dishes_category','dishes_category.id', '=', 'dishes.category_id')
                        ->leftjoin('dish_quantity','dish_quantity.dish_id', '=', 'dishes.id')
                        ->leftjoin('dishes_quantity','dishes_quantity.id', '=', 'dish_quantity.quantity_id')
                        ->select('dishes.id',
                                'dishes.name as dish_name',
                                'dishes.price as dish_price',
                                'dishes_category.name as dishes_category_name',
                                DB::raw("ifnull(dishes.image,'') as image"))
                        // ->where('dishes.name',$name)
                        ->where('restaurant.id',$restaurant_id)
                        ->groupBy('dishes.id')
                        ->with('dishQuantity')
                        // ->where('dishes.category_id',$category)
                        ->get();
        $this->data = $data;
        $this->status   = "true";
        $this->message  = 'messages.data_get';
        return $this->response();
    }

    public function getAuthUser($token){
        return JWTAuth::toUser($token);
    }
}
