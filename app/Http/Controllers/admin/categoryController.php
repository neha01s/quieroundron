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

//-- Main Controller start
class categoryController extends Controller {
#
#
#
#
#
#
//------------------========Main Categories Controller (START)===========-----------------//


	//-- View default category Panel
        public function categoryDefault() {

             return Redirect::to('admin/category');
        }


    //-- View Category Panel (Default case)
        public function category(Request $request) {

          $cateView = DB::table('mainCategories')->orderBy('idMainCategory', 'ASC')->get();
          return view('admin/category', [
              'cateView' => $cateView,
          ]);
        }


    //-- View Category Panel (View Add Category Form)
        public function addCate() {

          return Redirect::to('admin/category?caseC=addC');
        }


    //-- Submit Action of Add Category Form
        public function addCateSub(Request $request) {

	        $cateCheck = DB::table('mainCategories')->where('nameMainCategory', $request['addCateName'])->first();
	        $count=count($cateCheck);
	        //dd($count); 
	        if(@$count) {
	        	 Session::put('cateTitleMsz', 'Category with Name '.$request['addCateName'].' is already exists.');
	             return Redirect::to('admin/category?caseC=addC');
	        }
	        else{

	        	//-- Image Validations(start)

                    	$file1 = Input::file('addCateImg');
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
                          Session::put('addImgMszC', $abcd);
                          return Redirect::to('admin/category?caseC=addC');
                             
                        }
                        else{

                        $Path1=1;
                    
                        }
                  //-- Image Validations(end)

                  //-- Image Upload If Validation Pass(start)
                    if($Path1==1) {

                      $destinationPath1 = 'uploads/categories';
                      $filenameOLD = $file1->getClientOriginalName();
                      $randomRegisImage=rand(10,999999).time().rand(10,999999);
                      $filename1 = $randomRegisImage.$filenameOLD;
                      $upload_succes = $file1->move($destinationPath1, $filename1);

                  //-- Add Cms Page Code(Start)
                    if(!$upload_succes="") {


				        $data = array(
			                    'nameMainCategory' => $request['addCateName'],
			                    'imgMainCategory' => $filename1,
			                    'statusMainCategory' => $request['addCateStatus'],
			                    );
				            
				        $i = DB::table('mainCategories')->insert($data); 

			            if($i!=="") {
			              Session::put('cateAddMsz', 'Category Added Successfully');
			              return Redirect::to('admin/category');
			            }
			            else{
			              Session::put('cateAddMsz', 'Category Not Added');
			              return Redirect::to('admin/category'); 
			            }

			        }
    			}		
        	}
        }



    //-- Category Panel (Submit Delete-Category Form)
        public function delCate($id, $img) {

        	$delFilePath="uploads/categories/".$img;

          	$k=DB::table('mainCategories')->where('idMainCategory', $id)->delete();

	        if($k!=="") {

	        	$l=DB::table('subCategories')->where('idSubMainCategory', $id)->delete();

        		
        			//-- To Delete Added Image From Folder
		            //unlink(public_path($delFilePath));

		            Session::put('cateDelMsz', 'Category Deleted Successfully.');
		          	return Redirect::to('admin/category');
	
   
	        }
	        else {

	            Session::put('cateDelMsz', 'Category Not Deleted.');
	          	return Redirect::to('admin/category');
	        }

        }


    //-- Update Category (View)
        public function upCate($id) {

          return Redirect::to('admin/category?caseC=upC&IdC='.$id);

        }


    //-- Update Category (Submit)
        public function upCateSub(Request $request) {

        	if(!$request['upCateImg']==""){

                  $pathPass1="";
                  $pathPass2="";
                  
                 
                  //-- Image Validations(start)
                      $file2 = Input::file('upCateImg');
                
                          $Path2="";
                          $idHideCms=$request['upCate_id'];

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
                            Session::put('upImgMszCate', $abcde);
                            return Redirect::to('admin/category?caseC=upC&IdC='.$idHideCms);
                               
                          }
                          else{

                          $Path2=1;
                      
                          }
                    //-- Image Validations(end)

                    //-- Image Upload If Validation Pass(start)
                      if($Path2==1) {
                        $destinationPath2 = 'uploads/categories';
                        $filenameOLD2 = $file2->getClientOriginalName();
                        $randomRegisImage2=rand(10,999999).time().rand(10,999999);
                        $filename2 = $randomRegisImage2.$filenameOLD2;
                        $upload_succes2 = $file2->move($destinationPath2, $filename2);

                        //-- To Delete Added Image From Folder
                        $delFileUp="uploads/categories/".$request['hideImgCateUp'];
                        //unlink(public_path($delFileUp));
                        $pathPass1=1;
                      }
                    //-- Image Upload If Validation Pass(End)
                }
                else{
                  $pathPass2=1;
                  $filename2=$request['hideImgCateUp'];
                }


                //-- Add Cms Page Code(Start)
              if($pathPass2==1 || $pathPass1=1) {
							$post = $request->all();
							$datas = array(
							'nameMainCategory' => $post['upCateName'],
							'imgMainCategory' => $filename2,
							'statusMainCategory' => $post['upCateStatus'],
							);

							$j = DB::table('mainCategories')->where('idMainCategory', $post['upCate_id'])->update($datas);

				            if($j!=="") {
				            Session::put('cateUpMsz', 'Category Updated Successfully.');
				            return Redirect::to('admin/category');
				            }
				            else{
				              Session::put('cateUpMsz', 'Category Not Updated.');
				              return Redirect::to('admin/category?caseC=upC&IdC='.$idHideCms);
				            }

				    }
        }

//------------------========Main Categories Controller (END)===========-----------------//
#
#
#
#
#
#
//------------------========Sub Categories Controller (START)===========-----------------//

	    //-- Subcategories list (List View)
	        public function listSub($id) {

	          return Redirect::to('admin/category?caseC=listSubC&IdC='.$id);

	        }


	    //-- Add Subcategories (View Form)
	        public function addSubC($id) {

	          return Redirect::to('admin/category?caseC=addSubC&IdC='.$id);

	        }

	    //-- Submit Action of Add Sub Category Form
	        public function addSubCateSub(Request $request) {
		        $cateCheck3 = DB::table('subCategories')->where('idSubMainCategory', $request['addSubHiddenMain'])->where('nameSubCategory', $request['addSubCateName'])->first();
		        $count3=count($cateCheck3);
		        //dd($count); 
		        if(@$count3) {
		        	 Session::put('subCateTitleMsz', 'Sub Category with Name '.$request['addSubCateName'].' is already exists.');
		             return Redirect::to('admin/category?caseC=addSubC&IdC='.$request['addSubHiddenMain']);
		        }
		        else{

			        $data = array(
		                    'nameSubCategory' => $request['addSubCateName'],
		                    'statusSubCategory' => $request['addSubCateStatus'],
		                    'idSubMainCategory' => $request['addSubHiddenMain'],
		                    );
			            
			        $m = DB::table('subCategories')->insert($data); 
		            if($m!=="") {
		              Session::put('subCateAddMsz', 'Sub Category Added Successfully');
		              return Redirect::to('admin/category?caseC=listSubC&IdC='.$request['addSubHiddenMain']);
		            }
		            else{
		              Session::put('subCateAddMsz', 'Sub Category Not Added');
		              return Redirect::to('admin/category?caseC=listSubC&IdC='.$request['addSubHiddenMain']);
		            }
		        }
	        }


	    //-- Update Category (View)
        public function upSubC($id, $idMain) {
          return Redirect::to('admin/category?caseC=upSubC&IdMC='.$idMain.'&IdSC='.$id);
        }




      //-- Update Sub Category (Form Submit)
        public function upSubCsub(Request $request) {

    			$post = $request->all();
    			$datas = array(
    			'nameSubCategory' => $post['upSubCateName'],
    			'statusSubCategory' => $post['upSubCateStatus'],
    			);

    			$j = DB::table('subCategories')->where('idSubCategory', $post['upSubHiddenSub'])->where('idSubMainCategory', $post['upSubHiddenMain'])->update($datas);

                if($j!=="") {
                Session::put('ScateUpMsz', 'Category Updated Successfully.');
                return Redirect::to('admin/category?caseC=listSubC&IdC='.$post['upSubHiddenMain']); 
                }
                else{
                  Session::put('ScateUpMsz', 'Category Not Updated.');
                  return Redirect::to('admin/category?caseC=listSubC&IdC='.$post['upSubHiddenMain']); 
                }

        }




	    //-- Submit action to Delete SubCategory
	        public function delSubC($id, $idMain) {

	          $n=DB::table('subCategories')->where('idSubCategory', $id)->delete();

		        if($n!=="") {
		            Session::put('ScateDelMsz', 'Sub Category Deleted Successfully.');
		          	return Redirect::to('admin/category?caseC=listSubC&IdC='.$idMain);
		        }
		        else{
		            Session::put('ScateDelMsz', 'Sub Category Not Deleted.');
		          	return Redirect::to('admin/category?caseC=listSubC&IdC='.$idMain);
		        }

	        }



//------------------========Sub Categories Controller (END)===========-----------------//
#
#
#
#
#
#
}//-- Main Controller end







