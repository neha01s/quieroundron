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




class tempController extends Controller {

	//-- Contact Page View
		public function contact() {
			
			return view('tempPages/contact');
		}


	//-- Product Description Page View
		public function productDetail() {
			
			return view('tempPages/productDetail');
		}

	//-- Price And Covrage Page View
		public function priceCoverage() {
			
			return view('tempPages/priceCoverage');
		}

	//-- Video Page View
		public function video() {
			
			return view('tempPages/video');
		}





	//-- News Page View
		public function newsFront() {
			
			return view('tempPages/newsFront');
		}

	//-- Drone Product Page View
		public function droneFront() {
			
			return view('tempPages/droneFront');
		}

	//-- Write Review
		public function wriiteRe() {
			
			return view('tempPages/wriiteRe');
		}

	//-- Single News
		public function snglNews() {
	
			return view('tempPages/snglNews');
		}



	//-- aboutDrone
		public function aboutDrone() {
	
			return view('tempPages/aboutDrone');
		}

	//-- whyUs
		public function whyUs() {
	
			return view('tempPages/whyUs');
		}

	//-- ourTeam
		public function ourTeam() {
	
			return view('tempPages/ourTeam');
		}

	//-- aboutUs
		public function aboutUs() {
	
			return view('tempPages/aboutUs');
		}

	//-- prPolicy
		public function prPolicy() {
	
			return view('tempPages/prPolicy');
		}
		
   
}
