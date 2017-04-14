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


class adminController extends Controller {


  use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    protected $redirectTo = 'admin/index';
    /**
     * Create a new controller instance.
     *
     * @return void
    */
 
    public function login() {  
        //return view('admin/login'); //or just use the default login page

        return view('admin.login'); //or just use the default login page
    }  
  

    public function login_sub(Request $request)
  {
    
        $credentials = $this->getCredentials($request); 
        $email =$credentials['email'];
        $password = $credentials['password'];

        if (Auth::attempt(array('email' => $email, 'password' => $password, 'user_role' => 2)))  
        {  
            $result = DB::table('users')->get();
            Session::put('userRole', '2');
            return Redirect::to('admin/index')->with('success',$result); 
        }  
        else { 
       Session::put('msz', 'Email or Password is not correct.');
            return Redirect::to('admin/login'); 
        }  
    }  
    
    

    public function adminLogout()
    {
         Session::flush();
        return redirect('admin/login');
    }

    //---Index Page (home) 
  public function index() 
   {
      return view('admin/index');   
    }


}
