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




class frontCmsController extends Controller {

	//- Cms Page Redirect
		public function frontCmsDefault($id) {
			return Redirect::to('/frontCms?cfi='.$id);
	    }


	//-- Cms Page View
		public function frontCms() {
			return view('/frontCms');
	    }



	//- Cms Page Redirect
		public function frontCmsInnerDefault($id) {
			return Redirect::to('/frontInnerCms?cfii='.$id);
	    }


	//-- Cms Page View
		public function frontInnerCms() {
			return view('/frontInnerCms');
	    }

   
}

