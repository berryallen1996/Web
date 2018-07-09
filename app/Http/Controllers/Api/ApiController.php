<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;


class ApiController extends Controller
{
    //
    public function login(){
    	return $this->response();
    }
}
