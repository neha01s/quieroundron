<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller; // using controller class
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use Session;
use Auth;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\user;
use Illuminate\Support\Facades\Input;




class myAuthController extends Controller {

	public function loggedUserTest() {

		if(Auth::User()->status==1) {
			return Redirect::to('/homeView');
		}
		else{

			Session::put('userDisabled', 'Póngase en contacto con administrador');
			return Redirect::to('/logout');
		}

    }

}

