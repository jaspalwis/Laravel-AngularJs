<?php
namespace App\Http\Controllers;

use Auth;
use Hash;
use Session;
use Validator;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Logs;

class CustomAuthController extends Controller
{
	
	/* public function postLogin(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email', 'password' => 'required',
		]);

		$email  = $request->input('email');
        $pass   = $request->input('password');

		if (Auth::attempt(['email' => $email, 'password' => $pass], $request->has('remember'))) {
			return redirect()->intended($this->redirectPath());
		}

		return redirect('/login')
			->withInput($request->only('email', 'remember'))
			->withErrors([
				'email' => 'getFailedLoginMessage',
			]);
	} */

	public function fetchIPAddress(){
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

		
	public function postLogin(Request $request)
    {
		$rules = [
			'email'            => 'required|email',
			'password'         => 'required',
		];
		$messages = [
			'required'  => 'Your :attribute is required.',
			'email' => 'Please type valid email address.',
		];

		$validator = Validator::make($request->all(), $rules, $messages);
		
		if ($validator->fails()) {
            return redirect('/login')
                        ->withErrors($validator)
                        ->withInput();
        }
		
        $email  = $request->input('email');
        $pass   = $request->input('password');
        $remember   = $request->input('remember');
		
		$user_ip = $this->fetchIPAddress();
		//$user_ip  = '122.173.25.176';
		$ipArr = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$user_ip));
	
		$city		 =  $ipArr['geoplugin_city'];
		$state		 =  $ipArr['geoplugin_region'];
		$country	 =  $ipArr['geoplugin_countryName'];
		// $countryCode =  $ipArr['geoplugin_countryCode'];
		// $latitude	 =  $ipArr['geoplugin_latitude'];
		// $longitude	 =  $ipArr['geoplugin_longitude'];
		 
		$user_location = $country.','.$state.','.$city;
		
		
		if (Auth::attempt(['email' => $email, 'password' => $pass] , $remember )) {
            if (Auth::check()) {
				
				$logs = new Logs;
				$logs->user_ip = $user_ip;
				$logs->user_location = $user_location;
				$logs->email_address = $email;
				$logs->access_granted = 'Yes';
				$logs->save();
				
				return redirect('/home');
            } 
        }else{
			
			/*$useremail = User::where('email' , '=' ,  $email )->get()->count();
			
			if($useremail <= 0){ */
				
				$logs = new Logs;
				$logs->user_ip = $user_ip;
				$logs->user_location = $user_location;
				$logs->email_address = $email;
				$logs->access_granted = 'No';
				$logs->save();
				
				return redirect('/login')
					->withInput()
					->withErrors([
						'email' => 'These credentials do not match our records..',
					]);
				
			/* 	//Session::flash('email_error', 'Your E-Mail Address is wrong.');
			}else{
				//Session::flash('password_error', 'Your Password is wrong.');
			} */
			return redirect()->back()->withInput($request->only('email', 'remember'));
		}
    }
	
}
