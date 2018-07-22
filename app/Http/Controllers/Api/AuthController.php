<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Models\YearOrderId;
use App\Models\Restaurant;
use App\Models\Dish;
use App\Models\Plate;
use App\Models\DishQuantity;
use App\Models\CartOrder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\User;
use App\PasswordReset;
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
                $this->message  = 'messages.token_invalid';
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
                $this->message  = 'messages.token_invalid';
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
                $this->message  = 'messages.token_invalid';
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
        
        $state_id = isset($request->state_id) ? $request->state_id : '';
        $city_id = isset($request->city_id) ? $request->city_id : '';
        $locality_id = isset($request->locality_id) ? $request->locality_id : '';
        $pincode = isset($request->pincode) ? $request->pincode : '';
        $address = isset($request->address) ? $request->address : '';
        
        $builder = Restaurant::leftjoin('country','country.id_country', '=', 'restaurant.country_id')
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
                                DB::raw("ifnull(restaurant.image,'') as image"));
        if($state_id != ''){
            $builder = $builder->where('restaurant.state_id',$state_id);
        }
        if($city_id != ''){
            $builder = $builder->where('restaurant.city_id',$city_id);
        }
        if($locality_id != ''){
            $builder = $builder->where('restaurant.locality_id',$locality_id);
        }
        if($pincode != ''){
            $builder = $builder->where('restaurant.pincode',$pincode);
        }
        if($address != ''){
            $builder = $builder->where('address', 'like', '%' . $address . '%');
        }

        $data = $builder->paginate(10);
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
        $restaurant_id = isset($request->restaurant_id) ? $request->restaurant_id : '';
        $name = isset($request->name) ? $request->name : '';
        $category_id = isset($request->category_id) ? $request->category_id : '';
        
        $builder = Dish::join('dish_restaurant','dish_restaurant.dish_id', '=', 'dishes.id')
                        ->join('restaurant','restaurant.id', '=', 'dish_restaurant.restaurant_id')
                        ->join('dishes_category','dishes_category.id', '=', 'dishes.category_id')
                        ->select('dishes.id',
                                'dishes.name as dish_name',
                                'dishes_category.id as category_id',
                                'dishes.price as dish_price',
                                'dishes_category.name as dishes_category_name',
                                DB::raw("ifnull(dishes.image,'') as image"))
                        ->where('restaurant.id',$restaurant_id)
                        ->with('dishQuantity')->get();

        
        $data = $builder->groupBy('dishes_category_name');
        $this->data = $data;
        $this->status   = "true";
        $this->message  = 'messages.data_get';
        return $this->response();
    }

    /**
     * Static content
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function staticContent(Request $request)
    {
        $credentials = $request->only('type');
        $rules = [
            'type' => 'required|in:about,terms,privacy',
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $this->message    = $validator->messages()->first();
        }
        else{
            $data = DB::table('static_contents')->select('alias as type', 'title','description')->where('alias',$request->type)->first();
            $this->data = $data;
            $this->status   = "true";
            $this->message  = 'messages.data_get';
        }
        return $this->response();
    }


    /**
     * Add to cart
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request)
    {
        $credentials = $request->only('restaurant_id','item_id','shipping_date','quantity');
        $rules = [
            'restaurant_id' => 'required',
            'item_id' => 'required',
            'shipping_date' => 'required',
            'quantity' => 'required'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $this->message    = $validator->messages()->first();
        }
        else{
            if(!$request->token){
                $this->message  = 'Please perform the Login.';
            }else{
                $user = $this->getAuthUser($request->token);  
                if($user){
                    $isExist = CartOrder::where('user_id',$user->id)
                                        ->where('item_id',$request->item_id)
                                        ->first();
                    if(!$isExist){
                        $dishData = Dish::where('id', '=', $request->item_id)->first();
                        if($dishData){
                            $insert_data = [
                                'user_id'  => $user->id,
                                'restaurant_id'  => $request->restaurant_id,
                                'item_id'  => $dishData->id,
                                'shipping_date'  => $request->shipping_date,
                                'quantity' => $request->quantity,
                                'price' => $dishData->price,
                            ];

                            if($request->plate_id){
                                $plateData = Plate::join('dish_quantity','dish_quantity.plate_id', '=', 'plates.id')
                                    ->where('id', '=', $request->plate_id)
                                    ->where('dish_quantity.dish_id', '=', $dishData->id)
                                    ->select('plates.id as plate_id','dish_quantity.price')
                                    ->first();
                                if($plateData){
                                    $insert_data['plate_id'] = $plateData->plate_id;
                                    $insert_data['price'] = $plateData->price;
                                }else{
                                    $this->message  = 'messages.valid_plate_id';
                                    return $this->response();
                                }
                            }
                            $insert = CartOrder::create($insert_data);
                            $cartId = $insert->id;
                            $this->message  = 'messages.add_to_cart';
                        }

                    }else{
                        
                        $cartId = $isExist->id;
                        $quantity = $isExist->quantity+1;
                        CartOrder::where('id',$cartId)
                        ->update(['quantity'=>$quantity])
                                    ;
                        $this->message  = 'messages.already_in_cart';
                    }

                    $this->status   = "true";
                    $this->data = $this->getCartData($user->id);

                }else{
                    $this->message  = 'messages.token_invalid';
                }
            }
        }
        return $this->response();
    }

    /**
     * Cart List
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cartList(Request $request)
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
                $this->data = $this->getCartData($user->id);
                $this->status   = "true";
                $this->message  = 'messages.data_get';
            }else{
                $this->message  = 'messages.token_invalid';
            }
        }
        return $this->response();
        
    }

    /**
     * Remove from Cart List
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromCart(Request $request)
    {
        $credentials = $request->only('token','cart_id');
        $rules = [
            'token' => 'required',
            'cart_id' => 'required'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $this->message    = $validator->messages()->first();
        }
        else{
            $user = $this->getAuthUser($request->token);   
            if($user){
                $isExist = CartOrder::where('id',$request->cart_id)->first();
                if($isExist){
                    $cartData = CartOrder::where('id',$request->cart_id)
                                ->delete();  
                    $this->data = $this->getCartData($user->id);
                    $this->status   = "true";
                    $this->message  = 'messages.remove_from_cart';
                }else{
                    $this->message  = 'messages.item_not_found';
                }
                
            }else{
                $this->message  = 'messages.token_invalid';
            }
        }
        return $this->response();
        
    }


    public function getCartData($user_id){
        return CartOrder::where('user_id',$user_id)
                        ->join('users','users.id', '=', 'cart_order.user_id')
                        ->join('restaurant','restaurant.id', '=', 'cart_order.restaurant_id')
                        ->join('dishes','dishes.id', '=', 'cart_order.item_id')
                        ->leftJoin('plates','plates.id', '=', 'cart_order.plate_id')
                        ->select('cart_order.id as cart_id',
                                'restaurant.name as restaurant_name',
                                'dishes.name as item_name',
                                'dishes.id as item_id',
                                'restaurant.id as restaurant_id',
                                'plates.id as plate_id',
                                'plates.name as plate',
                                'cart_order.shipping_date',
                                'cart_order.quantity',
                                'cart_order.price',
                                'cart_order.created_at',
                                'cart_order.updated_at'
                                )
                        ->get(); 
    }

    public function getCartTotal($user_id){
        return CartOrder::where('user_id',$user_id)->sum('price');
    }

    /**
     * place the order
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function placeOrder(Request $request)
    {
        $credentials = $request->only('token','shipping_date','shipping_address');
        $rules = [
            'token' => 'required',
            'shipping_address' => 'required',
            'shipping_date' => 'required'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $this->message    = $validator->messages()->first();
        }
        else{
            $user = $this->getAuthUser($request->token);   
            $userId = $user->id;   
            $userEmail = $user->email;   
            if($user){
                $isExist = $this->getCartData($user->id);
                if($isExist){
                    $new_order_id = '';
                    $order_data = [
                        'year_order_id'=>$this->getYearOrderId(),
                        'user_id'=>$userId,
                        'payment_status'=>'completed',
                        'total'=>$this->getCartTotal($userId),
                        'shipping_address'=>$request->shipping_address,
                        'shipping_date'=>$request->shipping_date,
                        'order_status'=>'processed',
                        'delivery_status'=>'pending',
                        'additional_comments' => $request->additional_comments?$request->additional_comments:'',
                    ];
                    DB::transaction(function() use ($order_data,$userId, $userEmail,$isExist,&$new_order_id){
                        $new_order = Order::create($order_data);
                        $new_order_id = $new_order->id;

                        $items = [];
                        foreach($isExist as $item){
                            $items[] = [
                                'order_id'=>$new_order_id,
                                'user_id'=>$userId,
                                'email'=>$userEmail,
                                'restaurant_id'=>$item['restaurant_id'],
                                'item_id'=>$item['item_id'],
                                'price'=>$item['price'],
                                'quantity'=>$item['quantity'],
                                'plate_id'=>$item['plate_id'],
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=>date('Y-m-d H:i:s'),
                            ];
                        }
                        \DB::table('order_detail')->insert($items);
                        CartOrder::where('user_id',$userId)->delete();
                    
                    });
                    
                    $this->data = $this->getOrderData($new_order_id);
                    $this->status   = "true";
                    $this->message  = 'messages.placed_sucess';
                }else{
                    $this->message  = 'messages.cart_empty';
                }
                
            }else{
                $this->message  = 'messages.token_invalid';
            }
        }
        return $this->response();
        
    }

    public function getAuthUser($token){
        return JWTAuth::toUser($token);
    }

    public function getAuthUserId($token){
        return JWTAuth::toUser($token)->id;
    }

    public function getOrderData($id){
        return Order::find($id);
    }

    /**
    * Function will provide the ned order id according to year.
    */

    public function getYearOrderId(){

        $start_form = 1001;
        $year = date('Y');
        
        $YearOrderId = \DB::table('year_order_id');
        $maxOfYearId = YearOrderId::first([\DB::raw("max(year_order_id) as max_id")]); 
        if($maxOfYearId->max_id){
            $last_id = $maxOfYearId->max_id;
            $year = substr($last_id,0,2);
            $actualId = substr($last_id,2);
            $newOrderId = $actualId+1;          
            $year_order_id = $year.str_pad($newOrderId,6,"0",STR_PAD_LEFT);

            YearOrderId::create(['year_order_id'=>$year_order_id]);

        }else{          

            $year_order_id = date('y').str_pad($start_form,6,"0",STR_PAD_LEFT);
            YearOrderId::create(['year_order_id'=>$year_order_id]);
        }       
        return $year_order_id;
    }
}
