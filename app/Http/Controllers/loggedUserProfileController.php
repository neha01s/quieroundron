<?php
namespace App\Http\Controllers;
//use App\Http\Controllers\Controller; // using controller class

use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use Validator;
use Session;
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\user;
use Illuminate\Support\Facades\Input;
use File;



class loggedUserProfileController extends Controller {


#
#
#
#
######### View My Profile Page (Default Case)
    public function myProfile() {
        return view('/loggedUserProfile');
    }


#
#
#
#
######### View My Profile (View Info Case)
    public function myProfileView($id, $role) {
        return Redirect::to('/myProfile?a='.$id.'&b='.$role);
    }


#
#
#
#
######### View My Profile (View Form Case)
    public function chngPassLogged($id, $role) {

        return Redirect::to('/myProfile?a='.$id.'&b='.$role.'&c=cpw');
    }

#
#
#
#
######### Change Password-(Form Submit) 
    public function chngPassLoggedSub(Request $request) {
        $post = $request->all();
        $this->validate(
        $request, 
        [
            'oldPassword' => 'required|min:6',
            'newPassword' => 'required|min:6',
            'confirmed' => 'required|same:newPassword',
        ],
        [
            'oldPassword.required' => 'Campo requerido.',
            'oldPassword.min' => 'La contraseña debe tener un mínimo de 6 caracteres.',
            'newPassword.required' => 'Campo requerido',
            'newPassword.min' => 'La contraseña debe tener un mínimo de 6 caracteres.',
            'confirmed.required' => 'Campo requerido',
            'confirmed.same' => 'Las contraseñas no coinciden.',
        ]
        );

        $loggedUserId=Auth::user()->id;
        $loggedUserEmail=Auth::user()->email;
        $id=$_POST['hideUserId'];
        $role=$_POST['hideUserRole'];

        $credentials = [
            'email' => $loggedUserEmail,
            'password' => $_POST['oldPassword']
        ];

        

        if( Auth::attempt($credentials) ) {

            $newPassWord=$_POST['newPassword'];
            $datas = array(
                'password' => bcrypt($newPassWord),
                );

            $upPass = DB::table('users')->where('id', $loggedUserId)->update($datas);

            if($upPass!=="") {
                Session::put('changPassSuccess', 'Your Password is updated Successfully.');
                return Redirect::to('/myProfile?a='.$id.'&b='.$role);
            }
            else{
                Session::put('changPassError', 'Error: Please Try Again.');
                return Redirect::to('/myProfile?a='.$id.'&b='.$role.'&c=cpw');  
            }
           
        }else{
            Session::put('matchPass', 'Your Password is incorrect');
            return Redirect::to('/myProfile?a='.$id.'&b='.$role.'&c=cpw');
        }

    }



#
#
#
#
######### Edit User Profile (View Form Case)
    public function editUser($id, $role) {

        return Redirect::to('/myProfile?a='.$id.'&b='.$role.'&c=up');
    }


#
#
#
#
######### Edit Pilot Profile (View Form Case)
    public function editPilot($id, $role) {

        return Redirect::to('/myProfile?a='.$id.'&b='.$role.'&c=up');
    }


#
#
#
#
######### Edit Company Profile (View Form Case)
    public function editComp($id, $role) {

        return Redirect::to('/myProfile?a='.$id.'&b='.$role.'&c=up');
    }



#
#
#
#
######### Edit User Profile (Submit Form Case)
    public function editUserS(Request $request) {
        $post = $request->all();
        $this->validate(
        $request, 
        [
            'FirstName' => 'required|max:20',
            'LastName' => 'required|max:20',
            'country' => 'required',
            'Contact' => 'required',
        ],
        [
            'FirstName.required' => 'Campo requerido.',
            'FirstName.max' => 'Puede añadir un máximo de 20 caracteres.',
            'LastName.required' => 'Campo requerido.',
            'LastName.max' => 'Puede añadir un máximo de 20 caracteres.',
            'country.required' => 'Campo requerido.',
            'Contact.required' => 'Campo requerido.',
        ]
        );

        $fName=$_POST['FirstName'];
        $lName=$_POST['LastName'];
        $country=$_POST['country'];
        $mobile=$_POST['Contact'];
        $userId=$_POST['hideUserId'];
        $userRole=$_POST['hideUserRole'];

        $datas = array(
                'fname' => $fName,
                'lname' => $lName,
                'country_id' => $country,
                'mobile' => $mobile,
                );

        $upPass = DB::table('users')->where('id', $userId)->update($datas);

        if($upPass>0) {
            Session::put('upUserSuccess', 'Perfil Actualizado  Exitosamente.');
            return Redirect::to('/myProfile?a='.$userId.'&b='.$userRole);
        }else{
            Session::put('upUserFail', 'No Cambios Hecho.');
            return Redirect::to('/myProfile?a='.$userId.'&b='.$userRole.'&c=up');
        }

    }


#
#
#
#
######### Edit Pilot Profile (Submit Form Case)
    public function editPilotS(Request $request) {
        $path1= $path2 = $path3 = $path4 = $succeed1 = $succeed2 = 0;
        $input = Input::all();
        $post = $request->all();
        $this->validate(
        $request, 
        [
            'FirstNameP' => 'required|max:20',
            'LastNameP' => 'required|max:20',
            'ContactP' => 'required',
            'specialtyP' => 'max:100',
        ],
        [
            'FirstNameP.required' => 'Campo requerido.',
            'FirstNameP.max' => 'Puede añadir un máximo de 20 caracteres.',
            'LastNameP.required' => 'Campo requerido.',
            'LastNameP.max' => 'Puede añadir un máximo de 20 caracteres.',
            'specialtyP.max' => 'Puede añadir un máximo de  100 caracteres.',
            'ContactP.required' => 'Campo requerido.',
        ]
        );
            #
            #
            ##########- PILOT IMAGES VALIDATION CODE START

            //-- Id and Role Of User
                $id=$post['hideIdPilot'];
                $role=$post['hideRolePilot'];
                $allImgs=$post['fullOldImg'];
                $oldImgRemain=(isset($post['oldimg']))? $post['oldimg'] : "";
                $oldCrtRemain=(isset($post['oldcrt']))? $post['oldcrt'] : "";

            //-- Get Count Of New Images
                $newCount1= (isset($_FILES['newimg']['tmp_name']))? $_FILES['newimg']['tmp_name'] : "";
                if(!empty($newCount1)) {
                    $newCount = count(array_filter($newCount1));
                }else{
                    $newCount=0;
                }


            //-- Get Count Of Old Images
                $oldCount1= (isset($post['oldimg']))? $post['oldimg'] : "";
                if(!empty($oldCount1)){
                   $oldCount = count(array_filter($oldCount1, 'strlen'));
                }else{
                   $oldCount = 0;
                }


            //-- Total Count Of Images
                $countOld=$oldCount;
                $countNew=$newCount;
                $countTotal=$oldCount+$newCount;

                if($countNew > 0) {
                    $files1 = Input::file('newimg');
                    $files = array_filter($files1);
                    
                    if($countTotal < 6 ) {
                        
                        foreach ($files as $file) {
                            $rules = array('file' => 'required|mimes:jpeg,png|image|max:2000'); // 'required|mimes:png,gif,jpeg,txt,pdf,doc'
                            $validator = Validator::make(array('file'=> $file), $rules);
                            $validator = Validator::make(
                              array('file' => $file),
                              array('file' => 'required|mimes:jpeg,png|image|max:2000')
                            );

                            if(!$validator->passes()) {
                                $error_messages[] = 'File "' . $file->getClientOriginalName() . '":' . $validator->messages()->first('file');
                                $abc=json_encode($error_messages);             
                                Session::put('imgFroPilValid', $abc);
                                //return Redirect::to('/myProfile?a='.$id.'&b='.$role.'&c=up');
                                $path1=1;
                            }
                            else{
                                $succeed1=1;
                            }
                        }
                    }
                    else{
                        Session::put('imgFroPilValid', 'You Can Upload Maximum 5 Files');
                        //return Redirect::to('/myProfile?a='.$id.'&b='.$role.'&c=up');
                        $path2=1;
                    }
                }
                else{
                    $succeed1=1;
                }
            ##########- PILOT IMAGES VALIDATION CODE END
            #
            #
            #
            #
            #
            ##########- IF VALIDATION FAILED-(START)

            if($path1==1 || $path2==1){
                return Redirect::to('/myProfile?a='.$id.'&b='.$role.'&c=up'); die();
            } 

            ##########- IF VALIDATION FAILED-(END)
            #
            #
            #
            #
            #
            ##########- IF VALIDATION PASS THEN UPLOAD CODE-(START)

                if($succeed1==1) {
                    #
                    #
                    #-- Coma Seprate Old Pilot Image Names-(Start)
                        $oldImg1= (isset($post['oldimg']))? $post['oldimg'] : "";
                        if(!empty($oldImg1)) {
                            $oldImg=implode(',', $oldImg1);
                        }else{
                            $oldImg="";
                        }
                    #-- Coma Seprate Old Pilot Image Names-(End)
                    #
                    #
                    #
                    ###### Pilot Image Upload Code-(Start)
                    if($countNew>0){
                        $files51 = Input::file('newimg');
                        $files5 = array_filter($files51);

                        //-- foreach start --//
                        foreach ($files5 as $file6) { 
                          $destinationPath1 = 'uploads/imagesPilot/imgsPilots';
                          $filenameOLD = $file6->getClientOriginalName();
                          $randomRegisImage=rand(10,999999).time().rand(10,999999);
                          $filename1 = $randomRegisImage.$filenameOLD;
                          $upload_success1 = $file6->move($destinationPath1, $filename1);
                          //-- Create An Array For Pilot Image Names
                          $newPilotImg[] = $filename1;
                        }
                        //-- foreach end --//

                        //-- Coma Seprate New Pilot Image Names
                        $newPilotImgs=implode(',', $newPilotImg);
                    }
                    else{
                        $newPilotImgs="";
                    }

                    ###### Pilot Image Upload Code-(End)
                    #
                    #
                    #
                    ###### Combine Old And New Comma Seprated Strings Code-(Start)

                    //-- IMAGES
                    $strImg1=$oldImg;
                    $strImg2=$newPilotImgs;
                    $strImg3=$strImg1. "," .$strImg2;
                    $finalImg=trim($strImg3,",");
                                
                    ###### Combine Old And New Comma Seprated Strings Code-(End)
                    #
                    #
                    #  
                }

            ##########- IF VALIDATION PASS THEN UPLOAD CODE-(END)
            #
            #
            #
            #
            ##########--Upload Certificate Code-(Start)-(New)

                $oldCDL= (isset($post['oldCrtDL']))? $post['oldCrtDL'] : "";
                $oldCPL= (isset($post['oldCrtPL']))? $post['oldCrtPL'] : "";
                $oldCFI= (isset($post['oldCrtFI']))? $post['oldCrtFI'] : "";
                $cDLnew= (isset($post['crtDLnew']))? $post['crtDLnew'] : "";
                $cPLnew= (isset($post['crtPLnew']))? $post['crtPLnew'] : "";
                $cFInew= (isset($post['crtFInew']))? $post['crtFInew'] : "";

                //--Crt 1
                if($oldCDL==""){
                    if($cDLnew!==""){
                        $filesCDL1p = Input::file('crtDLnew');
                        $destinationPath1p = 'uploads/imagesPilot/certPilots';
                        $filenameOLDCDL1p = $filesCDL1p->getClientOriginalName();
                        $randomCrtDLp=rand(10,999999).time().rand(10,999999);
                        $filenameCDL1p = $randomCrtDLp.$filenameOLDCDL1p;
                        $upload_successCDLp = $filesCDL1p->move($destinationPath1p, $filenameCDL1p);
                        $CerDLfinalP = $filenameCDL1p;
                    }
                    else{
                        $CerDLfinalP="";
                    }
                }
                else{
                    $CerDLfinalP=$oldCDL;
                }


                //--Crt 2
                if($oldCPL==""){
                    if($cPLnew!==""){
                        $filesCPL1p = Input::file('crtPLnew');
                        $destinationPath2p = 'uploads/imagesPilot/certPilots';
                        $filenameOLDCPL1p = $filesCPL1p->getClientOriginalName();
                        $randomCrtPLp=rand(10,999999).time().rand(10,999999);
                        $filenameCPL1p = $randomCrtPLp.$filenameOLDCPL1p;
                        $upload_successCDLp = $filesCPL1p->move($destinationPath2p, $filenameCPL1p);
                        $CerPLfinalP = $filenameCPL1p;
                    }
                    else{
                        $CerPLfinalP="";
                    }
                }
                else{
                    $CerPLfinalP=$oldCPL;
                }


                //--Crt 2
                if($oldCFI==""){
                    if($cFInew!==""){
                        $filesCFI1p = Input::file('crtFInew');
                        $destinationPath3p = 'uploads/imagesPilot/certPilots';
                        $filenameOLDCFI1p = $filesCFI1p->getClientOriginalName();
                        $randomCrtFIp=rand(10,999999).time().rand(10,999999);
                        $filenameCFI1p = $randomCrtFIp.$filenameOLDCFI1p;
                        $upload_successCFIp = $filesCFI1p->move($destinationPath3p, $filenameCFI1p);
                        $CerFIfinalP = $filenameCFI1p;
                    }
                    else{
                        $CerFIfinalP="";
                    }
                }
                else{
                    $CerFIfinalP=$oldCFI;
                }

            ##########--Upload Certificate Code-(End)-(New)
            #
            #
            #
            #
            ##########- UPDATE DATABASE CODE-(START)
                $datas3 = array(
                    'fname' => $post['FirstNameP'],
                    'lname' => $post['LastNameP'],
                    'mobile' => $post['ContactP'],
                    'speciality' => $post['specialtyP'],
                    'description' => $post['descriptionP'],
                    'country_id' => $post['countryP'],
                    'video' => $post['videoP'],
                    'twitter_link' => $post['twiterP'],
                    'facebook_page' => $post['facebookP'],
                    'website' => $post['websiteP'],
                    'imagesUser' => $finalImg,
                    'crtDL' => $CerDLfinalP,
                    'crtPL' => $CerPLfinalP,
                    'crtFI' => $CerFIfinalP,
                    );

                $upPilot = DB::table('users')->where('id', $id)->where('status', 1)->update($datas3);

                $fromPil=$post['fromPil'];
                if($upPilot>0) {
                    if($fromPil=="vp"){  
                        return Redirect::to('/pilotView/'.$id); die();
                    }else{
                        Session::put('upPilotSuccess', 'Perfil Actualizado  Exitosamente.');
                        return Redirect::to('/myProfile?a='.$id.'&b='.$role); die();
                    }
                    
                }else{
                    if($fromPil=="vp"){
                        return Redirect::to('/pilotView/'.$id); die();
                    }else{
                        Session::put('upPilotFail', 'No Cambios Hecho.');
                        return Redirect::to('/myProfile?a='.$id.'&b='.$role.'&c=up'); die();
                    }
                }

            ##########- UPDATE DATABASE CODE-(END)
            #
            #
            #  
    }

##############################################################################################################################
#
#
#
#
######### Edit Pilot Company (Submit Form Case)
    public function editCompS(Request $request) {

        $path5= $path6 = $path7 = $path8 = $succeed3 = $succeed4 = 0;
        $input = Input::all();
        $post = $request->all();
        $this->validate(
        $request, 
        [
            'nameC' => 'required|max:20',
            'ContactC' => 'required',
            'specialtyC' => 'max:100',
        ],
        [
            'nameC.required' => 'Campo requerido.',
            'nameC.max' => 'Puede añadir un máximo de 20 caracteres.',
            'specialtyC.max' => 'Puede añadir un máximo de  100 caracteres.',
            'ContactC.required' => 'Campo requerido.',
        ]
        );

            #
            #
            #
            #
            #
            ##########- COMPANY IMAGES VALIDATION CODE START

            //-- Id and Role Of User
                $idC=$post['hideIdComp'];
                $roleC=$post['hideRoleComp'];
                $allImgsC=$post['fullOldImgC'];
                $oldImgRemainC=(isset($post['oldimgC']))? $post['oldimgC'] : "";
                $oldCrtRemainC=(isset($post['oldcrtC']))? $post['oldcrtC'] : "";

            //-- Get Count Of New Images
                $newCountC1= (isset($_FILES['newimgC']['tmp_name']))? $_FILES['newimgC']['tmp_name'] : "";
                if(!empty($newCountC1)) {
                    $newCountC = count(array_filter($newCountC1));
                }else{
                    $newCountC=0;
                }

            //-- Get Count Of Old Images
                $oldCountC1= (isset($post['oldimgC']))? $post['oldimgC'] : "";
                if(!empty($oldCountC1)){
                   $oldCountC = count(array_filter($oldCountC1, 'strlen'));
                }else{
                   $oldCountC = 0;
                }

            //-- Total Count Of Images
                $countOldC=$oldCountC;
                $countNewC=$newCountC;
                $countTotalC=$oldCountC+$newCountC;

                if($countNewC > 0) {
                    $filesC1 = Input::file('newimgC');
                    $filesC = array_filter($filesC1);
                    
                    if($countTotalC < 6 ) {

                        foreach ($filesC as $fileC) {
                          $rules = array('fileC' => 'required|mimes:jpeg,png|image|max:2000'); // 'required|mimes:png,gif,jpeg,txt,pdf,doc'
                          $validator = Validator::make(array('fileC'=> $fileC), $rules);

                          $validator = Validator::make(
                              array('fileC' => $fileC),
                              array('fileC' => 'required|mimes:jpeg,png|image|max:2000')
                           );

                          if(!$validator->passes()) {

                            $error_messages[] = 'File "' . $fileC->getClientOriginalName() . '":' . $validator->messages()->first('fileC');
                            $abc=json_encode($error_messages);             
                            Session::put('imgFroComValid', $abc);
                            //return Redirect::to('/myProfile?a='.$idC.'&b='.$roleC.'&c=up');
                            $path5=1;
                          }
                          else{
                            $succeed3=1;
                          }
                        }
                    }
                    else{
                        Session::put('imgFroComValid', 'You Can Upload Maximum 5 Files');
                        //return Redirect::to('/myProfile?a='.$idC.'&b='.$roleC.'&c=up');
                        $path6=1;
                    }
                }
                else{
                    $succeed3=1;
                }
            ##########- COMPANY IMAGES VALIDATION CODE END   
            #
            #
            #
            #
            #
            ##########- IF VALIDATION FAILED-(START)

            if($path5==1 || $path6==1){
                return Redirect::to('/myProfile?a='.$idC.'&b='.$roleC.'&c=up'); die();
            } 

            ##########- IF VALIDATION FAILED-(END)
            #
            #
            #
            #
            #
            ##########- IF VALIDATION PASS THEN UPLOAD CODE-(START)

                if($succeed3==1) {

                    //-- Coma Seprate Old COMPANY Image Names
                    $oldImgC1= (isset($post['oldimgC']))? $post['oldimgC'] : "";
                    if(!empty($oldImgC1)) {
                        $oldImgC=implode(',', $oldImgC1);
                    }else{
                        $oldImgC="";
                    }
                    #
                    #
                    #
                    ###### COMPANY Image Upload Code-(Start)
                    if($countNewC>0){
                        $filesC51 = Input::file('newimgC');
                        $filesC5 = array_filter($filesC51);

                        //-- foreach start --//
                        foreach ($filesC5 as $fileC6) { 
                          $destinationPathC1 = 'uploads/imagesCompany/imgsComp';
                          $filenameOLDC = $fileC6->getClientOriginalName();
                          $randomRegisImageC=rand(10,999999).time().rand(10,999999);
                          $filenameC1 = $randomRegisImageC.$filenameOLDC;
                          $upload_successC1 = $fileC6->move($destinationPathC1, $filenameC1);

                          //-- Create An Array For COMPANY Image Names
                          $newCompImgC[] = $filenameC1;
                        }
                        //-- foreach end --//

                        //-- Coma Seprate New COMPANY Image Names
                        $newCompImgsC=implode(',', $newCompImgC);
                    }
                    else{
                        $newCompImgsC="";
                    }

                    ###### COMPANY Image Upload Code-(End)
                    #
                    #
                    #
                    ###### Combine Old And New Comma Seprated Strings Code-(Start)

                    //-- IMAGES
                    $strImgC1=$oldImgC;
                    $strImgC2=$newCompImgsC;
                    $strImgC3=$strImgC1. "," .$strImgC2;
                    $finalImgC=trim($strImgC3,",");
                                
                    ###### Combine Old And New Comma Seprated Strings Code-(End)
                    #
                    #
                    #  
                }

            ##########- IF VALIDATION PASS THEN UPLOAD CODE-(END)
            #
            #
            #
            #
            #
            ##########--Upload Certificate Code-(Start)-(New)-(Company)

                $oldCDLcmp= (isset($post['oldCrtDLCmp']))? $post['oldCrtDLCmp'] : "";
                $oldCPLcmp= (isset($post['oldCrtPLCmp']))? $post['oldCrtPLCmp'] : "";
                $oldCFIcmp= (isset($post['oldCrtFICmp']))? $post['oldCrtFICmp'] : "";
                $cDLnewCmp= (isset($post['crtDLnewCmp']))? $post['crtDLnewCmp'] : "";
                $cPLnewCmp= (isset($post['crtPLnewCmp']))? $post['crtPLnewCmp'] : "";
                $cFInewCmp= (isset($post['crtFInewCmp']))? $post['crtFInewCmp'] : "";


                //--Crt 1-(Company)
                if($oldCDLcmp==""){
                    if($cDLnewCmp!==""){
                        $filesCDL1c = Input::file('crtDLnewCmp');
                        $destinationPath1c = 'uploads/imagesCompany/compCert';
                        $filenameOLDCDL1c = $filesCDL1c->getClientOriginalName();
                        $randomCrtDLc=rand(10,999999).time().rand(10,999999);
                        $filenameCDL1c = $randomCrtDLc.$filenameOLDCDL1c;
                        $upload_successCDLc = $filesCDL1c->move($destinationPath1c, $filenameCDL1c);
                        $CerDLfinalC = $filenameCDL1c;
                    }
                    else{
                        $CerDLfinalC="";
                    }
                }
                else{
                    $CerDLfinalC=$oldCDLcmp;
                }


                //--Crt 2-(Company)
                if($oldCPLcmp==""){
                    if($cPLnewCmp!==""){
                        $filesCPL1c = Input::file('crtPLnewCmp');
                        $destinationPath2c = 'uploads/imagesCompany/compCert';
                        $filenameOLDCPL1c = $filesCPL1c->getClientOriginalName();
                        $randomCrtPLc=rand(10,999999).time().rand(10,999999);
                        $filenameCPL1c = $randomCrtPLc.$filenameOLDCPL1c;
                        $upload_successCDLc = $filesCPL1c->move($destinationPath2c, $filenameCPL1c);
                        $CerPLfinalC = $filenameCPL1c;
                    }
                    else{
                        $CerPLfinalC="";
                    }
                }
                else{
                    $CerPLfinalC=$oldCPLcmp;
                }


                //--Crt 3-(Company)
                if($oldCFIcmp==""){
                    if($cFInewCmp!==""){
                        $filesCFI1c = Input::file('crtFInewCmp');
                        $destinationPath3c = 'uploads/imagesCompany/compCert';
                        $filenameOLDCFI1c = $filesCFI1c->getClientOriginalName();
                        $randomCrtFIc=rand(10,999999).time().rand(10,999999);
                        $filenameCFI1c = $randomCrtFIc.$filenameOLDCFI1c;
                        $upload_successCFIc = $filesCFI1c->move($destinationPath3c, $filenameCFI1c);
                        $CerFIfinalC = $filenameCFI1c;
                    }
                    else{
                        $CerFIfinalC="";
                    }
                }
                else{
                    $CerFIfinalC=$oldCFIcmp;
                }

            ##########--Upload Certificate Code-(End)-(New)-(Company)
            #
            #
            #
            #
            ##########- UPDATE DATABASE CODE-(START)   
            $datas3 = array(

                'company_name' => $post['nameC'],
                'address' => $post['addressC'],
                'mobile' => $post['ContactC'],
                'speciality' => $post['specialtyC'],
                'description' => $post['descriptionC'],
                'country_id' => $post['countryC'],
                'video' => $post['videoC'],
                'twitter_link' => $post['twiterC'],
                'facebook_page' => $post['facebookC'],
                'website' => $post['websiteC'],
                'imagesUser' => $finalImgC,
                'crtDL' => $CerDLfinalC,
                'crtPL' => $CerPLfinalC,
                'crtFI' => $CerFIfinalC,
                );

            $upPilot = DB::table('users')->where('id', $idC)->where('status', 1)->update($datas3);

            $fromComp=$post['upFromC'];

            if($upPilot>0) {
                if($fromComp=="vc"){
                    return Redirect::to('/compView/'.$idC); die();
                }else{
                    Session::put('upCompSuccess', 'Perfil Actualizado  Exitosamente.');
                    return Redirect::to('/myProfile?a='.$idC.'&b='.$roleC); die();
                }

            }else{
                if($fromComp=="vc"){
                    return Redirect::to('/compView/'.$idC); die();
                }else{
                    Session::put('upCompFail', 'No Cambios Hecho.');
                return Redirect::to('/myProfile?a='.$idC.'&b='.$roleC.'&c=up'); die();
                }
            }
            ##########- UPDATE DATABASE CODE-(END)
    }

##############################################################################################################################################


    //-- Delete Pilot Image
    // public function delImgP($id, $role, $img) {

    //     $getImg1 = DB::table('users')->where('id', $id)->where('status', 1)->get();

    //     //-- Foreach Start
    //         foreach ($getImg1 as $getImg) {

    //                 $imgAll=$getImg->imagesUser;
    //                 $imgCut=$img;
                  
    //             //-- To Delete Added Image From Folder
    //                 $delFilePath="uploads/imagesPilot/imgsPilots/".$imgCut;
    //                 unlink(public_path($delFilePath));


    //             //-- Explode And Remove Selected Image From String
    //                 $parts = explode(',', $imgAll);
    //                 while(($i = array_search($imgCut, $parts)) !== false) {
    //                     unset($parts[$i]);
    //                 }
    //                 $imgToUpdate= implode(',', $parts);


    //             //-- Update New String In Database
    //                 $datas = array(
    //                     'imagesUser' => $imgToUpdate,
    //                     );
    //                 $upImg = DB::table('users')->where('id', $id)->where('status', 1)->update($datas);
    //                 if($upImg!=="") {
    //                     Session::put('delImgMsz', 'Image Is Deleted Successfully.');
    //                     return Redirect::to('/myProfile?a='.$id.'&b='.$role.'&c=up');
    //                 }else{
    //                     Session::put('delImgMsz', 'Error: Image Is Not Deleted Successfully, Please Try Again.');
    //                     return Redirect::to('/myProfile?a='.$id.'&b='.$role.'&c=up');
    //                 }
    //         }
    //     //-- Foreach End

    // }
}

