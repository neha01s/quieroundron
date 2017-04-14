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



class registerController extends Controller {


  	
	//--Check If User Registration Email Already Exist
		public function chkUserRegEmail(Request $request) {
			$post = $request->all();
			$emailU=$_POST['emailID'];
		    $emailRptUser = DB::table('users')->where('email', $emailU)->get();
	        if(!$emailRptUser==""){
				$EMchk[] = array(
						    'EpathChk'  => 1
							);
				return response()->json([
		            'EMchk' => $EMchk
		            ]);

	        } else {
	        	$EMchk[] = array(
						    'EpathChk'  => 0
							);
				return response()->json([
		            'EMchk' => $EMchk
		            ]);
	        }
		}


	//--Check If Pilot Registration Email Already Exist
		public function chkPilRegEmail(Request $request) {
			$post = $request->all();
			$emailP=$_POST['emailIDP'];
		    $emailRptPil = DB::table('users')->where('email', $emailP)->get();
	        if(!$emailRptPil==""){
				$EMchkP[] = array(
						    'EpathChkP'  => 1
							);
				return response()->json([
		            'EMchkP' => $EMchkP
		            ]);

	        } else {
	        	$EMchkP[] = array(
						    'EpathChkP'  => 0
							);
				return response()->json([
		            'EMchkP' => $EMchkP
		            ]);
	        }
		}



	//--Check If Company Registration Email Already Exist
		public function chkCompRegEmail(Request $request) {
			$post = $request->all();
			$emailC=$_POST['emailIDC'];
		    $emailRptCpm = DB::table('users')->where('email', $emailC)->get();
	        if(!$emailRptCpm==""){
				$EMchkC[] = array(
						    'EpathChkC'  => 1
							);
				return response()->json([
		            'EMchkC' => $EMchkC
		            ]);

	        } else {
	        	$EMchkC[] = array(
						    'EpathChkC'  => 0
							);
				return response()->json([
		            'EMchkC' => $EMchkC
		            ]);
	        }
		}

#
#
}
