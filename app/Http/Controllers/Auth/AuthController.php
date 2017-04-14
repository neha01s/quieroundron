<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Session;
use App\Http\Requests;
use DB;
use Auth;
use App\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use PDF;
use URL;

//-- Main controller if start --//
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/loggedUserTest';

    //-- To change logout redirection
    protected $redirectAfterLogout = '/login';



    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */



    protected function getFailedLoginMessage()
    {
    return 'Nombre de usuario y contraseña no coinciden';
    }


   //-- Validation array if start --//
    protected function validator(array $data)
    {

        //-- User Registration
        if($data['userRole']==1) {
            Session::put('test', '1');
            return Validator::make($data, [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'country' => 'required|max:255',
                'password' => 'required|min:6|confirmed',
            ]);
        }



        //-- Pilot Registration
        if($data['userRole']==3) {
            Session::put('test', '2');
            return Validator::make($data, [
               'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'country' => 'required|max:255',
                'password' => 'required|min:6|confirmed',
                //'image' => 'required|array|mimes:jpeg,png,jpg,svg|max:200',

            ]);
        }


        //-- Company Registration
        if($data['userRole']==4) {
            Session::put('test', '3');
            return Validator::make($data, [
                'email' => 'required|email|max:255|unique:users',
                'country' => 'required|max:255',
                'password' => 'required|min:6|confirmed',
                'company_name' => 'required|max:255',
                'website' => 'required|max:255',
                'address' => 'required',
            ]);
        }

    }
   //-- Validation array if end --//

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */


   //-- Create array if start --//
    protected function create(array $data)
    {


        //-- User Registration
        if($data['userRole']==1) {


            Session::put('test', '1');

            $user = User::create([
                'fname' => $data['first_name'],
                'lname' => $data['last_name'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'country_id' => $data['country'],
                'user_role' => $data['userRole'],
                'password' => bcrypt($data['password']),
                'status' => '1',
                'userRemind' => '(user_role 1 for user)|(user_role 2 for admin)|(user_role 3 for pilot)|(user_role 4 for company)',
            ]);
            
            Mail::send('emailView.uRegisterMail', ['data' => $data], function($message) use ($data)
            {
                $message->from('amit.kumar@ldh.01s.in', "Quiero Un Drone");
                $message->subject("Welcome to Quiero Un Drone");
                $message->to($data['email']);
            });

            Session::put('createUserSuccess', 'Su cuenta se ha creado correctamente.');
            return $user;
        }

       
      
      //-- Pilot Registration start --//
        if($data['userRole']==3) {
        Session::put('test', '2');


          // //print_r($_FILES['image']); die();

          // //--======================  Image Validations Start ===================== --//

          // //-- User Image Validations
          //   $filesImg1 = Input::file('image');

          //   $files = array_filter($filesImg1);

          //    $Icount = count(array_filter($_FILES['image']['name']));
            
          //     //-- foreach start --//

          //     $errorPath1="";
          //     $errorPath2="";
          //     $errorPath3="";
          //     $errorPath4="";
          //     $succeddPath1="";
          //     $succeddPath2="";

              
          //     if(count(array_filter($_FILES['image']['name'])) < 6 ) {

          //       $f = 0;
          //       for($i = 0; $i=$Icount; $i++) {
          //         $rules = array('file' => 'required|mimes:jpeg,png|image|max:200'); // 'required|mimes:png,gif,jpeg,txt,pdf,doc'
          //         $validator = Validator::make(array('file'=> $files[$f]), $rules);

          //         $validator = Validator::make(
          //             array('file' => $files[$f]),
          //             array('file' => 'required|mimes:jpeg,png|image|max:200')
          //          );

          //         if(!$validator->passes()) { 

          //           $error_messages[] = 'File "' . $files[$f]->getClientOriginalName() . '":' . $validator->messages()->first('file');

          //           $abc=json_encode($error_messages);             
          //           Session::put('imgValid', $abc);
          //           $errorPath1=1;   
          //         }
          //         $f++;
          //       }
          //     }
          //     else{
          //       Session::put('imageCount', 'You Can Upload Maximum 5 Files');
          //       $errorPath3=1;
          //     }
          // //-- User Image Validations


          // //-- Certificate Image Validations
          //   $filesCRT1 = Input::file('certificates');

          //   $filesCerti = array_filter($filesCRT1);

          //     //-- foreach start --//

          //     if(count(array_filter($_FILES['certificates']['name'])) < 4 ) {
          //       foreach ($filesCerti as $file2) {

          //         // Validate each file
          //         $rules = array('file' => 'required|mimes:jpeg,png|image|max:200'); // 'required|mimes:png,gif,jpeg,txt,pdf,doc'
          //         $validator2 = Validator::make(array('file'=> $file2), $rules);

          //         $validator2 = Validator::make(
          //             array('file' => $file2),
          //             array('file' => 'required|mimes:jpeg,png|image|max:200')
          //          );


          //        //--- inner if-else start --//
          //         if(!$validator2->passes()) {

          //           $error_messages2[] = 'File "' . $file2->getClientOriginalName() . '":' . $validator2->messages()->first('file');

          //               $jkl=json_encode($error_messages2);             
          //               Session::put('certiValid', $jkl);
          //               $errorPath2=1;
          //         }
          //       }
          //     }
          //     else{
          //       Session::put('certiCount', 'You Can Upload Maximum 3 Files');
          //       $errorPath4=1;
          //     }
          // //-- Certificate Image Validations


          //     if($errorPath1==1 || $errorPath2==1 || $errorPath3==1 || $errorPath4==1) {
          //       return Redirect::to('register')->send();
          //     }
          //     else{
          //       $succeddPath1=1;
          //       $succeddPath2=1;
          //     }

          // //--======================  Image Validations End ===================== --//


          // //---================ Pilot Image Upload Code Start ===================---//

          //   if($succeddPath1==1 && $succeddPath2==1) {

          //       $files1 = Input::file('image');
                
          //      //-- foreach start --//
          //       $j = 0;
          //       for($i = 0; $i=$Icount; $i++) {



          //         $destinationPath1 = 'uploads/imagesPilot/imgsPilots';
          //         $filenameOLD = $files1[$j]->getClientOriginalName();
          //         $randomRegisImage=rand(10,999999).time().rand(10,999999);
          //         $filename1 = $randomRegisImage.$filenameOLD;

          //         $upload_success1 = $files1[$j]->move($destinationPath1, $filename1);

          //         //-- Create An Array For Pilot Image Names
          //         $userImg[] = $filename1;

          //       }

          //       //-- Coma Seprate Pilot Image Names
          //       $userImgs=implode(',', $userImg);

                
          //      //-- foreach end --//
             
          // //---================ Pilot Image Upload Code End ===================---//  


          // //---================ Pilot Certificate Upload Code Start ===================---//

          //       $filesCerti = Input::file('certificates');
              
          //      //-- foreach start --//
          //       foreach ($filesCerti as $file2) {

          //         $destinationPath = 'uploads/imagesPilot/certPilots';
          //         $filenameOrig = $file2->getClientOriginalName();
          //         $randomRegisCerti=rand(10,999999).time().rand(10,999999);

          //         $filename=$randomRegisCerti.$filenameOrig;

          //         $upload_success = $file2->move($destinationPath, $filename);

          //         //-- Create An Array For Certificate Image Names
          //         $certImg[] = $filename;
                  
          //       }
          //      //-- foreach end --//


          //       //-- Coma Seprate Certificate Image Names
          //       $certImgs=implode(',', $certImg);


          //---================ Pilot Certificate Upload Code End ===================---//


        //----- Images -----//

            $files2 = Input::file('image');
            $files = array_filter($files2);
            //-- foreach start --//
            foreach ($files as $file) { 
              $destinationPath = 'uploads/imagesPilot/imgsPilots';
              $filenameOLD = $file->getClientOriginalName();
              $randomPostImage=rand(10,999999).time().rand(10,999999);
              $filename = $randomPostImage.$filenameOLD;
              $upload_success = $file->move($destinationPath, $filename);

            //-- Create An Array For Pilot Image Names
              $userImg[] = $filename;
            }
            //-- Coma Seprated Pilot Image Names
            $userImgs=implode(',', $userImg);



        //----- Certificates -----//

          //--Certificate 1
            $crtDLchk= (isset($data['crtDL']))? $data['crtDL'] : "";
            if($crtDLchk!==""){
              $filesCDL1 = Input::file('crtDL');
              $destinationPath1 = 'uploads/imagesPilot/certPilots';
              $filenameOLDCDL1 = $filesCDL1->getClientOriginalName();
              $randomPostCrtDL=rand(10,999999).time().rand(10,999999);
              $filenameCDL1 = $randomPostCrtDL.$filenameOLDCDL1;
              $upload_successCDL = $filesCDL1->move($destinationPath1, $filenameCDL1);
              $certDL = $filenameCDL1;
            }
            else{
                $certDL="";
            }
            
          //--Certificate 2
            $crtPLchk= (isset($data['crtPL']))? $data['crtPL'] : "";
            if($crtPLchk!==""){
              $filesCPL1 = Input::file('crtPL');
              $destinationPathPL1 = 'uploads/imagesPilot/certPilots';
              $filenameOLDCPL1 = $filesCPL1->getClientOriginalName();
              $randomPostCrtPL=rand(10,999999).time().rand(10,999999);
              $filenameCPL1 = $randomPostCrtPL.$filenameOLDCPL1;
              $upload_successCPL = $filesCPL1->move($destinationPathPL1, $filenameCPL1);
              $certPL = $filenameCPL1;
            }
            else{
                $certPL="";
            }

          //--Certificate 3
            $crtFIchk= (isset($data['crtFI']))? $data['crtFI'] : "";
            if($crtFIchk!==""){
              $filesCFI1 = Input::file('crtFI');
              $destinationPathFI = 'uploads/imagesPilot/certPilots';
              $filenameOLDCFI1 = $filesCFI1->getClientOriginalName();
              $randomPostCrtFI=rand(10,999999).time().rand(10,999999);
              $filenameCFI1 = $randomPostCrtFI.$filenameOLDCFI1;
              $upload_successCFI = $filesCFI1->move($destinationPathFI, $filenameCFI1);
              $certFI = $filenameCFI1;
            }
            else{
                $certFI="";
            }


            $pilot = User::create([
                'fname' => $data['first_name'],
                'lname' => $data['last_name'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'country_id' => $data['country'],
                'user_role' => $data['userRole'],
                'password' => bcrypt($data['password']),
                'website' => $data['website'],
                'imagesUser' => $userImgs,
                'video' => $data['video'],
                'twitter_link' => $data['twitter_link'],
                'facebook_page' => $data['facebook_page'],
                'speciality' => $data['speciality'],
                'crtDL' => $certDL,
                'crtPL' => $certPL,
                'crtFI' => $certFI,
                'description' => $data['description'],
                'status' => '1',
                'userRemind' => '(user_role 1 for user)|(user_role 2 for admin)|(user_role 3 for pilot)|(user_role 4 for company)',
            ]);

            Mail::send('emailView.pRegisterMail', ['data' => $data], function($message) use ($data)
            {
                $message->from('amit.kumar@ldh.01s.in', "Quiero Un Drone");
                $message->subject("Welcome to Quiero Un Drone");
                $message->to($data['email']);
            });
            Session::put('createUserSuccess', 'Su cuenta se ha creado correctamente.');
            return $pilot;
        }
       //-- Pilot Registration end --//


        //-- Company Registration
        if($data['userRole']==4) {
            Session::put('test', '3');

          //   //-- User Image Validations
          //   $files = Input::file('image');
          //     //-- foreach start --//

          //     $errorPath1="";
          //     $errorPath2="";
          //     $errorPath3="";
          //     $errorPath4="";
          //     $succeddPath1="";
          //     $succeddPath2="";
    
          //     if(count(array_filter($_FILES['image']['name'])) < 6 ) {
          //       foreach ($files as $file) {
          //         $rules = array('file' => 'required|mimes:jpeg,png|image|max:200'); // 'required|mimes:png,gif,jpeg,txt,pdf,doc'
          //         $validator = Validator::make(array('file'=> $file), $rules);

          //         $validator = Validator::make(
          //             array('file' => $file),
          //             array('file' => 'required|mimes:jpeg,png|image|max:200')
          //          );

          //         if(!$validator->passes()) { 

          //           $error_messages[] = 'File "' . $file->getClientOriginalName() . '":' . $validator->messages()->first('file');

          //           $hij=json_encode($error_messages);             
          //           Session::put('imgValidComp', $hij);
          //           $errorPath1=1;   
          //         }
          //       }
          //     }
          //     else{
          //       Session::put('imageCountComp', 'You Can Upload Maximum 5 Files');
          //       $errorPath3=1;
          //     }
          // //-- User Image Validations

          // //-- Certificate Image Validations
          //   $filesCerti = Input::file('certificates');
          //     //-- foreach start --//

          //     if(count(array_filter($_FILES['certificates']['name'])) < 4 ) {
          //       foreach ($filesCerti as $file2) {

          //         // Validate each file
          //         $rules = array('file' => 'required|mimes:jpeg,png|image|max:200'); // 'required|mimes:png,gif,jpeg,txt,pdf,doc'
          //         $validator2 = Validator::make(array('file'=> $file2), $rules);

          //         $validator2 = Validator::make(
          //             array('file' => $file2),
          //             array('file' => 'required|mimes:jpeg,png|image|max:200')
          //          );


          //        //--- inner if-else start --//
          //         if(!$validator2->passes()) {

          //           $error_messages2[] = 'File "' . $file2->getClientOriginalName() . '":' . $validator2->messages()->first('file');

          //               $klm=json_encode($error_messages2);             
          //               Session::put('certiValidComp', $klm);
          //               $errorPath2=1;
          //         }
          //       }
          //     }
          //     else{
          //       Session::put('certiCountComp', 'You Can Upload Maximum 3 Files');
          //       $errorPath4=1;
          //     }
          // //-- Certificate Image Validations


          //     if($errorPath1==1 || $errorPath2==1 || $errorPath3==1 || $errorPath4==1) {
          //       return Redirect::to('register')->send();
          //     }
          //     else{
          //       $succeddPath1=1;
          //       $succeddPath2=1;
          //     }

          // //--======================  Image Validations End ===================== --//


          // //---================ Company Image Upload Code Start ===================---//

          //   if($succeddPath1==1 && $succeddPath2==1) {

                $files5 = Input::file('imageC');
                $files1 = array_filter($files5);
                
              //-- foreach start --//
                foreach ($files1 as $file1) { 

                  $destinationPath1 = 'uploads/imagesCompany/imgsComp';
                  $filenameOLD = $file1->getClientOriginalName();
                  $randomRegisImage=rand(10,999999).time().rand(10,999999);
                  $filename1 = $randomRegisImage.$filenameOLD;

                  $upload_success1 = $file1->move($destinationPath1, $filename1);

                  //-- Create An Array For Company Image Names
                  $userImg[] = $filename1;

                }
              //-- foreach end --//

              //-- Coma Seprate Company Image Names
                $userImgs=implode(',', $userImg);

                
               
             
          //---================ Company Image Upload Code End ===================---//  


          //---================ Company Certificate Upload Code Start ===================---// cCrtdL cCrtPL cCrtFI

                //-- Certificate 1 Company
                  $cCrtDLchk= (isset($data['cCrtdL']))? $data['cCrtdL'] : "";
                  if($cCrtDLchk!==""){
                    $filesCDL1c = Input::file('cCrtdL');
                    $destinationPathDLc = 'uploads/imagesCompany/compCert';
                    $filenameOLDCDLc = $filesCDL1c->getClientOriginalName();
                    $randomPostCrtDLc=rand(10,999999).time().rand(10,999999);
                    $filenameCDLc = $randomPostCrtDLc.$filenameOLDCDLc;
                    $upload_successCFI = $filesCDL1c->move($destinationPathDLc, $filenameCDLc);
                    $certDLc = $filenameCDLc;
                  }
                  else{
                      $certDLc="";
                  }


                //-- Certificate 2 Company
                  $cCrtPLchk= (isset($data['cCrtPL']))? $data['cCrtPL'] : "";
                  if($cCrtPLchk!==""){
                    $filesCPL1c = Input::file('cCrtPL');
                    $destinationPathPLc = 'uploads/imagesCompany/compCert';
                    $filenameOLDCPLc = $filesCPL1c->getClientOriginalName();
                    $randomPostCrtPLc=rand(10,999999).time().rand(10,999999);
                    $filenameCPLc = $randomPostCrtPLc.$filenameOLDCPLc;
                    $upload_successCFI = $filesCPL1c->move($destinationPathPLc, $filenameCPLc);
                    $certPLc = $filenameCPLc;
                  }
                  else{
                      $certPLc="";
                  }


                //-- Certificate 3 Company
                  $cCrtFIchk= (isset($data['cCrtFI']))? $data['cCrtFI'] : "";
                  if($cCrtFIchk!==""){
                    $filesCFI1c = Input::file('cCrtFI');
                    $destinationPathFIc = 'uploads/imagesCompany/compCert';
                    $filenameOLDCFIc = $filesCFI1c->getClientOriginalName();
                    $randomPostCrtFIc=rand(10,999999).time().rand(10,999999);
                    $filenameCFIc = $randomPostCrtFIc.$filenameOLDCFIc;
                    $upload_successCFI = $filesCFI1c->move($destinationPathFIc, $filenameCFIc);
                    $certFIc = $filenameCFIc;
                  }
                  else{
                      $certFIc="";
                  }

          //---================ Company Certificate Upload Code End ===================---//


            $company = User::create([

                'company_name' => $data['company_name'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'country_id' => $data['country'],
                'address' => $data['address'],
                'user_role' => $data['userRole'],
                'password' => bcrypt($data['password']),
                'website' => $data['website'],
                'imagesUser' => $userImgs,
                'video' => $data['video'],
                'twitter_link' => $data['twitter_link'],
                'facebook_page' => $data['facebook_page'],
                'speciality' => $data['speciality'],
                'crtDL' => $certDLc,
                'crtPL' => $certPLc,
                'crtFI' => $certFIc,
                'description' => $data['description'],
                'status' => '1',
                'userRemind' => '(user_role 1 for user)|(user_role 2 for admin)|(user_role 3 for pilot)|(user_role 4 for company)',
            ]);

            Mail::send('emailView.cRegisterMail', ['data' => $data], function($message) use ($data)
            {
                $message->from('amit.kumar@ldh.01s.in', "Quiero Un Drone");
                $message->subject("Welcome to Quiero Un Drone");
                $message->to($data['email']);
            });
            Session::put('createUserSuccess', 'Su cuenta se ha creado correctamente.');
            return $company;
          }
    }
   //-- Create array if end --//

}
//-- Main controller if end --//



