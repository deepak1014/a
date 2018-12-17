<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class PassportController extends Controller
{
	public $successStatus = 200;

	public function login(Request $request)
	{
		if(Auth::attempt(['email'=>$request->email,'password'=>$request->password]))
		{
			$user = Auth::user();

			$success['token'] = $user->createToken('a')->accessToken;

			return response()->json(['success'=>$success,$this->successStatus]);
		}
		else
		{
			return response()->json(['error'=>'Unauthorise'],401);
		}
	}

	public function register(Request $request)
	{

		$this->validate($request,[
		'name'=>'required',
		'email'=>'required|email',
		'password'=>'required',
		'c_password'=>'required|same:password',
		]);
		
	
		$input = $request->all();
		$input['password'] = bcrypt($request->password);

		$user = User::create($input);

		$success['token'] = $user->createToken('a')->accessToken;
		$success['name'] = $user->name;

		return response()->json(['success'=>$success,$this->successStatus]);
	}

	public function getDetails()
	{
		$user = Auth::user();
		return response()->json(['success'=>$user],$this->successStatus);

	}


	
}