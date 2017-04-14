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
use Validator;



class newsController extends Controller {

	//-- View List Of Active Pilots On Frontend
		public function viewNews($id) {

		    $newsView = DB::table('news')->where('id_news', $id)->where('status_news', 1)->get();
	 
        	return view('news/newsView', [
					  'newsView' => $newsView,
					]);   
		}
	
#
#
}





