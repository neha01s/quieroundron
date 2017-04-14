<?php
namespace App\Http\Controllers\admin; //admin add
use App\Http\Controllers\Controller; // using controller class
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use Session;
use Auth;
use Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\user;
use Illuminate\Support\Facades\Input;
use File;
use Storage;



//-- Main Controller If Start
class cmsController extends Controller {

#
#
#
#
#
#
#
#
#
#
###########################################################---CMS MAIN Page CODE (START)---#######################################################################

#######
      #
      #
      #
      #### Redirect To View CMS Panel(Admin)
            public function cmsDefault() {
            	return Redirect::to('admin/cms');
            }

#######
      #
      #
      #
      #### View List Of CMS Pages List(Admin -- Default Case)
            public function cms(Request $request) {

              $cmsDefault = DB::table('cms')->orderBy('idCMS', 'ASC')->get();
              
              return view('admin/cms', [

              	  'cmsDefault' => $cmsDefault,
                  
              ]);
            }

#######
      #
      #
      #
      #### Disable Active CMS Page From Admin Panel
            public function cmsDis(Request $request, $id) {

              $post = $request->all();
              $datas = array(
              'statusCMS' => 0,
              );

              $z = DB::table('cms')->where('idCMS', $id)->update($datas);

              if($z!=="") {

                Session::put('mszCmsDefault', 'Selected CMS Page Is Disabled Successfully.');
                return Redirect::to('admin/cms');
              }
              else{
                Session::put('mszCmsDefault', 'Error: Selected CMS Page Is Not Disabled.');
                return Redirect::to('admin/cms');
              }

            }

#######
      #
      #
      #
      #### Enable Inactive CMS Page From Admin Panel
            public function cmsEna(Request $request, $id) {

              $post = $request->all();
              $datas = array(
              'statusCMS' => 1,
              );

              $y = DB::table('cms')->where('idCMS', $id)->update($datas);

              if($y!=="") {

                Session::put('mszCmsDefault', 'Selected CMS Page Is Enabled Successfully.');
                return Redirect::to('admin/cms');
              }
              else{
                Session::put('mszCmsDefault', 'Error: Selected CMS Page Is Not Enabled.');
                return Redirect::to('admin/cms');
              }

            }

#######
      #
      #
      #    
      #### Delete Inactive CMS Page From Admin Panel
              public function cmsDel(Request $request, $id, $img) {
              
              //-- Added Image Path For This Page
                $delFilePath="uploads/imagesCms/".$img;

                $x=DB::table('cms')->where('idCMS', $id)->delete();

                if($x!=="") {

                  //-- To Delete Added Image From Folder
                    unlink(public_path($delFilePath));

                    Session::put('mszCmsDefault', 'Selected CMS Page Is Deleted Successfully.');
                    return Redirect::to('admin/cms');
                }
                else{
                  Session::put('mszCmsDefault', 'Selected CMS Page Is Not Deleted Successfully.');
                  return Redirect::to('admin/cms');
                }

              }

#######
      #
      #
      #
      #### Add CMS Page From Admin Panel

          //-- addCmsSub If Start
            public function addCmsSub(Request $request) {

              $cmsChk = DB::table('cms')->where('titleCMS', $request['addCmsTitle'])->first();
              $countCms=count($cmsChk);
              
              //-- Count Code Start
                  if(@$countCms) {
                        Session::put('addCmsMsz', 'CMS Page with Name '.$request['addCmsTitle'].' is already exists.');
                        return Redirect::to('admin/cms?cmsCase=addCms');
                  }
                  else{

                      //-- Image Validations(start)
                        $file1 = Input::file('addCmsImg');
                  
                            $Path1="";
                            $rules = array('file1' => 'required|mimes:jpeg,png|image|max:200'); // 'required|mimes:png,gif,jpeg,txt,pdf,doc'
                            $validator = Validator::make(array('file1'=> $file1), $rules);

                            $validator = Validator::make(
                                array('file1' => $file1),
                                array('file1' => 'required|mimes:jpeg,png|image|max:1000')
                             );

                            if(!$validator->passes()) {
                              $Path1=0;

                              $error_messages[] = 'File "' . $file1->getClientOriginalName() . '":' . $validator->messages()->first('file1');

                              $abcd=json_encode($error_messages);             
                              Session::put('addImgMsz', $abcd);
                              return Redirect::to('admin/cms?cmsCase=addCms');
                                 
                            }
                            else{

                            $Path1=1;
                        
                            }
                      //-- Image Validations(end)

                      //-- Image Upload If Validation Pass(start)
                        if($Path1==1) {

                          $destinationPath1 = 'uploads/imagesCms';
                          $filenameOLD = $file1->getClientOriginalName();
                          $randomRegisImage=rand(10,999999).time().rand(10,999999);
                          $filename1 = $randomRegisImage.$filenameOLD;
                          $upload_succes = $file1->move($destinationPath1, $filename1);

                            //-- Add Cms Page Code(Start)
                                if(!$upload_succes="") {
                                  $data = array(
                                            'titleCMS' => $request['addCmsTitle'],
                                            'contentCMS' => $request['addCmsContent'],
                                            'imageCMS' => $filename1,
                                            'statusCMS' => $request['addCmsStatus'],
                                            );
                                      
                                  $w = DB::table('cms')->insert($data); 

                                      if($w!=="") {
                                        Session::put('mszCmsDefault', 'CMS Page Added Successfully');
                                        return Redirect::to('admin/cms');
                                      }
                                      else{
                                        Session::put('addCmsMsz', 'CMS Page Not Added');
                                        return Redirect::to('admin/cms?cmsCase=addCms'); 
                                      }

                                }
                                else{
                                  echo "Not done"; die();
                                }
                            //-- Add Cms Page Code(End)

                          }
                      //-- Image Upload If Validation Pass(End)

                  }
              //-- Count Code Start

            }
          //-- addCmsSub If End


#######
      #
      #
      #
      ####  Redirect to Edit CMS Page Form on Admin Panel
            public function cmsEdit(Request $request, $id) {

              return Redirect::to('admin/cms?cmsCase=updateCms&cmsId='.$id);
            }

#######
      #
      #
      #
      #### Update CMS Page From Admin Panel
            public function upCmsSub(Request $request) {

                if(!$request['upCmsImg']==""){

                  $pathPass1="";
                  $pathPass2="";
                  
                 
                  //-- Image Validations(start)
                      $file2 = Input::file('upCmsImg');
                
                          $Path2="";
                          $idHideCms=$request['cmsIdHide'];

                          $rules = array('file2' => 'required|mimes:jpeg,png|image|max:200'); // 'required|mimes:png,gif,jpeg,txt,pdf,doc'
                          $validator2 = Validator::make(array('file2'=> $file2), $rules);

                          $validator2 = Validator::make(
                              array('file2' => $file2),
                              array('file2' => 'required|mimes:jpeg,png|image|max:1000')
                           );

                          if(!$validator2->passes()) {
                            $Path2=0;

                            $error_messages2[] = 'File "' . $file2->getClientOriginalName() . '":' . $validator2->messages()->first('file2');

                            $abcde=json_encode($error_messages2);             
                            Session::put('upImgMsz', $abcde);
                            return Redirect::to('admin/cms?cmsCase=updateCms&cmsId='.$idHideCms);
                               
                          }
                          else{

                          $Path2=1;
                      
                          }
                    //-- Image Validations(end)

                    //-- Image Upload If Validation Pass(start)
                      if($Path2==1) {

                        $destinationPath2 = 'uploads/imagesCms';
                        $filenameOLD2 = $file2->getClientOriginalName();
                        $randomRegisImage2=rand(10,999999).time().rand(10,999999);
                        $filename2 = $randomRegisImage2.$filenameOLD2;
                        $upload_succes2 = $file2->move($destinationPath2, $filename2);

                        //-- To Delete Added Image From Folder
                          $delFileUp="uploads/imagesCms/".$request['cmsImgHide'];
                          unlink(public_path($delFileUp));

                        $pathPass1=1;

                      }
                    //-- Image Upload If Validation Pass(End)


                }
                else{
                  $pathPass2=1;
                  $filename2=$request['cmsImgHide'];
                }


                //-- Add Cms Page Code(Start)
                    if($pathPass2==1 || $pathPass1=1) {
                      $data = array(
                                'titleCMS' => $request['upCmsTitle'],
                                'contentCMS' => $request['upCmsContent'],
                                'imageCMS' => $filename2,
                                'statusCMS' => $request['upCmsStatus'],
                                );
                        
                      $idHideCms=$request['cmsIdHide'];  
                      $v = DB::table('cms')->where('idCMS', $idHideCms)->update($data);

                          if($v!=="") {
                            Session::put('mszCmsDefault', 'CMS Page Updated Successfully');
                            return Redirect::to('admin/cms');
                          }
                          else{
                            Session::put('upCmsMsz', 'CMS Page Not Updated');
                            return Redirect::to('admin/cms?cmsCase=updateCms&cmsId='.$idHideCms); 
                          }

                    }
                    else{
                      echo "Not done"; die();
                    }
                //-- Add Cms Page Code(End)

                
            }


###########################################################---CMS MAIN Page CODE (END)---#########################################################################
#
#
#
#
#
#
#
#
#
#
##########################################################---CMS Inner Page CODE (START)---#######################################################################


#######
      #
      #
      #
      #### View List Of Inner CMS Pages List In Main Page(Admin -- Default Case)
            public function cmsInnerList(Request $request, $id) {

              //$innerCmsList = DB::table('cmsSubPage')->where('idCmsSubMain', $id)->orderBy('idCmsSub', 'ASC')->get();
              
              return Redirect::to('admin/cms?cmsCase=listCmsInner&cmsId='.$id);
            }

#######
      #
      #
      #
      #### Add A New Inner CMS Page In Main Page(Admin -- View Form Case)
            public function innerCmsAdd(Request $request, $id) {

              return Redirect::to('admin/cms?cmsCase=addCmsInner&cmsId='.$id);
            }

#######
      #
      #
      #
      #### Add A New Inner CMS Page In Main Page(Admin -- Submit Case)
            public function addInnerCmsSub(Request $request) {

              $innerCmsChk = DB::table('cmsSubPage')->where('titleCmsSub', $request['addInnerCmsTitle'])->first();
              $countCmsInner=count($innerCmsChk);
              
              //-- Count Code Start
                  if(@$countCmsInner) {
                        Session::put('addInnerCmsMsz', 'A CMS Sub-Page with Name '.$request['addInnerCmsTitle'].' is already exists.');
                        return Redirect::to('admin/cms?cmsCase=addCmsInner&cmsId='.$request['hiddenIdMainCms']);
                  }
                  else{

                      //-- Image Validations(start)
                        $file3 = Input::file('addInnerCmsImg');
                  
                            $Path3="";
                            $rules = array('file3' => 'required|mimes:jpeg,png|image|max:200'); // 'required|mimes:png,gif,jpeg,txt,pdf,doc'
                            $validator = Validator::make(array('file3'=> $file3), $rules);

                            $validator = Validator::make(
                                array('file3' => $file3),
                                array('file3' => 'required|mimes:jpeg,png|image|max:1000')
                             );

                            if(!$validator->passes()) {
                              $Path3=0;

                              $error_messages[] = 'File "' . $file3->getClientOriginalName() . '":' . $validator->messages()->first('file3');

                              $abcd3=json_encode($error_messages);             
                              Session::put('addInnerCmsImgMsz', $abcd3);
                              return Redirect::to('admin/cms?cmsCase=addCms');
                                 
                            }
                            else{

                            $Path3=1;
                        
                            }
                      //-- Image Validations(end)

                      //-- Image Upload If Validation Pass(start)
                        if($Path3==1) {

                          $destinationPath3 = 'uploads/imagesCms/innerCmsImgs';
                          $filenameOLD3 = $file3->getClientOriginalName();
                          $randomRegisImage3=rand(10,999999).time().rand(10,999999);
                          $filename3 = $randomRegisImage3.$filenameOLD3;
                          $upload_succes3 = $file3->move($destinationPath3, $filename3);

                            //-- Add Cms Page Code(Start)
                                if(!$upload_succes3="") {
                                  $data = array(
                                            'idCmsSubMain' => $request['hiddenIdMainCms'],
                                            'titleCmsSub' => $request['addInnerCmsTitle'],
                                            'contentCmsSub' => $request['addInnerCmsContent'],
                                            'imageCmsSub' => $filename3,
                                            'statusCmsSub' => $request['addInnerCmsStatus'],
                                            );
                                      
                                  $w = DB::table('cmsSubPage')->insert($data); 

                                      if($w!=="") {
                                        Session::put('mszInnerCmsDefault', 'CMS Inner Page Added Successfully');
                                        return Redirect::to('admin/cms?cmsCase=listCmsInner&cmsId='.$request['hiddenIdMainCms']);
                                      }
                                      else{
                                        Session::put('addInnerCmsMsz', 'CMS Inner Page Not Added');
                                        return Redirect::to('admin/cms?cmsCase=addCmsInner&cmsId='.$request['hiddenIdMainCms']);
                                      }

                                }
                                else{
                                  echo "Error During Image Uploading"; die();
                                }
                            //-- Add Cms Page Code(End)

                          }
                      //-- Image Upload If Validation Pass(End)

                  }
              //-- Count Code Start

           } 

#######
      #
      #
      #
      #### Enable Inner CMS Page (Admin -- Submit Case)
           public function innerCmsEna(Request $request, $id, $idMain) {

              $post = $request->all();
              $datas = array(
              'statusCmsSub' => 1,
              );

              $y = DB::table('cmsSubPage')->where('idCmsSub', $id)->update($datas);

              if($y!=="") {

                Session::put('mszInnerCmsDefault', 'Selected Inner CMS Page Is Enabled Successfully.');
                return Redirect::to('admin/cms?cmsCase=listCmsInner&cmsId='.$idMain);
              }
              else{
                Session::put('mszInnerCmsDefault', 'Error: Selected Inner CMS Page Is Not Enabled.');
                return Redirect::to('admin/cms?cmsCase=listCmsInner&cmsId='.$idMain);
              }

            }

#######
      #
      #
      #
      #### Disable Inner CMS Page (Admin -- Submit Case)
            public function innerCmsDis(Request $request, $id, $idMain) {

              $post = $request->all();
              $datas = array(
              'statusCmsSub' => 0,
              );

              $z = DB::table('cmsSubPage')->where('idCmsSub', $id)->update($datas);

              if($z!=="") {

                Session::put('mszInnerCmsDefault', 'Selected Inner CMS Page Is Disabled Successfully.');
                return Redirect::to('admin/cms?cmsCase=listCmsInner&cmsId='.$idMain);
              }
              else{
                Session::put('mszInnerCmsDefault', 'Error: Selected Inner CMS Page Is Not Disabled.');
                return Redirect::to('admin/cms?cmsCase=listCmsInner&cmsId='.$idMain);
              }

            }

#######
      #
      #
      #
      #### Delete Inner CMS Page (Admin -- Submit Case)
            public function innerCmsDel(Request $request, $id, $img, $idMain) {
              
              //-- Added Image Path For This Page
                $delFilePath="uploads/imagesCms/innerCmsImgs/".$img;

                $x=DB::table('cmsSubPage')->where('idCmsSub', $id)->delete();

                if($x!=="") {

                  //-- To Delete Added Image From Folder
                    unlink(public_path($delFilePath));

                    Session::put('mszInnerCmsDefault', 'Selected Inner CMS Page Is Deleted Successfully.');
                    return Redirect::to('admin/cms?cmsCase=listCmsInner&cmsId='.$idMain);
                }
                else{
                  Session::put('mszInnerCmsDefault', 'Selected Inner CMS Page Is Not Deleted Successfully.');
                  return Redirect::to('admin/cms?cmsCase=listCmsInner&cmsId='.$idMain);
                }

              }


#######
      #
      #
      #
      #### Update Inner CMS Page (Admin -- Show Form Case)
            public function innerCmsEdit(Request $request, $id, $idMain) {

              return Redirect::to('admin/cms?cmsCase=upCmsInner&cmsId='.$id.'&cmsMainId='.$idMain);
            }



#######
      #
      #
      #
      #### Update Inner CMS Page (Admin -- Submit Case)

              public function InnerCmsEditSub(Request $request) {

                if(!$request['innerUpCmsImg']==""){

                  $pathPass1="";
                  $pathPass2="";
                  
                 
                  //-- Image Validations(start)
                      $file2 = Input::file('innerUpCmsImg');
                
                          $Path2="";
                          $idHideCmsInner=$request['innerCmsIdHide'];
                          $idHideCmsMain=$request['mainInnerCmsIdHide'];

                          $rules = array('file2' => 'required|mimes:jpeg,png|image|max:200'); // 'required|mimes:png,gif,jpeg,txt,pdf,doc'
                          $validator2 = Validator::make(array('file2'=> $file2), $rules);

                          $validator2 = Validator::make(
                              array('file2' => $file2),
                              array('file2' => 'required|mimes:jpeg,png|image|max:1000')
                           );

                          if(!$validator2->passes()) {
                            $Path2=0;

                            $error_messages2[] = 'File "' . $file2->getClientOriginalName() . '":' . $validator2->messages()->first('file2');

                            $abcde=json_encode($error_messages2);             
                            Session::put('innerUpImgMsz', $abcde);
                            return Redirect::to('admin/cms?cmsCase=upCmsInner&cmsId='.$idHideCmsInner.'&cmsMainId='.$idHideCmsMain);
                               
                          }
                          else{

                          $Path2=1;
                      
                          }
                    //-- Image Validations(end)

                    //-- Image Upload If Validation Pass(start)
                      if($Path2==1) {

                        $destinationPath2 = 'uploads/imagesCms/innerCmsImgs/';
                        $filenameOLD2 = $file2->getClientOriginalName();
                        $randomRegisImage2=rand(10,999999).time().rand(10,999999);
                        $filename2 = $randomRegisImage2.$filenameOLD2;
                        $upload_succes2 = $file2->move($destinationPath2, $filename2);

                        //-- To Delete Added Image From Folder
                          $delFileUp="uploads/imagesCms/innerCmsImgs/".$request['innerCmsImgHide'];
                          unlink(public_path($delFileUp));

                        $pathPass1=1;

                      }
                    //-- Image Upload If Validation Pass(End)


                }
                else{
                  $pathPass2=1;
                  $filename2=$request['innerCmsImgHide'];
                }


                //-- Add Cms Page Code(Start)
                    if($pathPass2==1 || $pathPass1=1) {
                      $data = array(
                                'titleCmsSub' => $request['innerUpCmsTitle'],
                                'contentCmsSub' => $request['innerUpCmsContent'],
                                'imageCmsSub' => $filename2,
                                'statusCmsSub' => $request['innerUpCmsStatus'],
                                );
                        
                      $idInner=$request['innerCmsIdHide'];
                      $idInnerMain=$request['mainInnerCmsIdHide'];

                      $v = DB::table('cmsSubPage')->where('idCmsSub', $idInner)->update($data);

                          if($v!=="") {
                            Session::put('mszInnerCmsDefault', 'CMS Page Updated Successfully');
                            return Redirect::to('admin/cms?cmsCase=listCmsInner&cmsId='.$idInnerMain);
                          }
                          else{
                            Session::put('upInnerCmsMsz', 'CMS Page Not Updated');
                            return Redirect::to('admin/cms?cmsCase=upCmsInner&cmsId='.$idInner.'&cmsMainId='.$idHideCmsMain); 
                          }

                    }
                    else{
                      echo "Not done"; die();
                    }
                //-- Add Cms Page Code(End)

                
              }
          



###########################################################---CMS Inner Page CODE (END)---########################################################################
#
#
#
#
#
#
#
#
#
#
}
//-- Main Controller If Start





