<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;

use App\User;
use App\Http\Controllers\Controller;
use App\Helpers\UUIDHelper;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller (for APIs)
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    protected function username($username){
    	return filter_var($username, FILTER_VALIDATE_EMAIL) !== false ? "email": "username";
    }

    public function login(Request $request){
    	$this->validate($request, [
            "username" => 'required', 'password' => 'required'
        ]);

    	$requestData = $request->only(["username", "password"]);
    	$credentials = [$this->username($requestData["username"]) => $requestData['username'], "password" => $requestData["password"]];
    	if(Auth::guard()->attempt($credentials, false)){
    		$user = Auth::guard()->user();
			if($user->api_token == null){
	    		$user->api_token = UUIDHelper::generate();
	    		$user->save();
	    	}
	    	return $user;
    	}else{
    		return response()->json(["error" => __("auth.failed") ], 404);
    	}
    }

    public function logout(Request $request){
    	$user = $request->user();
    	$user->api_token = null;
    	$user->save();
    	return response()->json([], 204);
    }
}
