<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;


class AdminController extends Controller
{
	public function login(){
		$data['page_title'] = $data['title'] = 'Login';
		if(Auth::check()){
			return redirect('administrator/dashboard');
		}
		return view('backend.login')->with($data);
	}



	/**
     * Authenticate the user for login.
     * @param  $request
     * @return \Illuminate\Http\Response
     */
	public function authenticate(Request $request){

		$validator = \Validator::make($request->all(), [
			'email' => 'required|email',
			'password' =>'required'
		],[
			'email.required' => 'Please enter email address.',
			'email.email' => 'Please enter valid email address.',
			'password.required' => 'Please enter password.'
		]);

		if ($validator->passes()) {
			if(Auth::attempt(['email' => $request->email,'password' => $request->password])){

				if(Auth::user()->is_verified != '1'){
					Auth::logout();
					$request->session()->flash('alert', sprintf(ALERT_DANGER,'Your account has been blocked, please contact to administrator.'));
					return redirect()->back()->withErrors($validator)->withInput();
				}else{
					return redirect('admin/dashboard');
				}
				

			}else{
				$request->session()->flash('alert', sprintf(ALERT_DANGER,'Email & Password combination is wrong. Try Again.'));
				return redirect()->back()->withErrors($validator)->withInput();
			}
		}else{
			return redirect()->back()->withErrors($validator)->withInput();
		}

	}

	public function dashboard(){
		$data['page_title'] = $data['title'] = 'Dashboard';
		
		$id = Auth::user()->id;


  
		return view('backend.page.dashboard')->with($data);
	}
}