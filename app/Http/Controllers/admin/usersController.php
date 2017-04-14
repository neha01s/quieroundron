<?php
namespace App\Http\Controllers\admin; //admin add
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




class usersController extends Controller {

    //-- Redirect To View Registered Users Panel(Admin)
        public function usersDefault() {
        	return Redirect::to('admin/user');
        }


    //-- View List Of Registered Users List(Admin)
        public function users(Request $request) {
          $userView = DB::table('users')->where('user_role', 1)->orderBy('id', 'ASC')->get();
          
          $pilotView = DB::table('users')->where('user_role', 3)->orderBy('id', 'ASC')->get();
        
          $companyView = DB::table('users')->where('user_role', 4)->orderBy('id', 'ASC')->get();
          return view('admin/users', [

          	  'userView' => $userView,
              'pilotView' => $pilotView,
              'companyView' => $companyView,
          ]);
        }


    //-- Disable Active User From Admin Panel
        public function disableUser(Request $request, $id, $role) {


          Session::put('roleToRedirect', $role);

          $post = $request->all();
          $datas = array(
          'status' => 0,
          );

          $z = DB::table('users')->where('id', $id)->update($datas);

          if($z!=="") {
            return Redirect::to('admin/user');
          }
          else{
            echo "error"; die();
            return Redirect::to('admin/user');
          }

        }


    //-- Enable Inactive User From Admin Panel
        public function enableUser(Request $request, $id, $role) {

          Session::put('roleToRedirect', $role);

        	$post = $request->all();
          $datas = array(
          'status' => 1,
          );

          $z = DB::table('users')->where('id', $id)->update($datas);

          if($z!=="") {
            return Redirect::to('admin/user');
          }
          else{
            echo "error"; die();
            return Redirect::to('admin/user');
          }

        }



    //-- View Selected User In Admin Panel
        public function viewUser($id, $role) {
          Session::put('roleToRedirect', $role);

          return Redirect::to('admin/user?caseUsers=viewUser&idusers='.$id.'&roleUser='.$role);

        }


}

