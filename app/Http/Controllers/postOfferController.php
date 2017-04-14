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



class postOfferController extends Controller {

	//-- Post a Offer Page View
		public function postOffer() {
			return view('postoffer/postOffer');
		}

  	
	//-- Show Subcategories on click of main category-(with AJAX)
		public function getSubCate(Request $request) {
			$post = $request->all();
		    $id=$_POST['mainId'];
		    $subCate = DB::table('subCategories')->where('idSubMainCategory', $id)->where('statusSubCategory', 1)->get();

	        if($request->ajax()){
	            if(!$subCate==""){	
	                return response()->json([
	                'subCate' => $subCate
	                ]);
	            }  
	        }
		}



	//-- Get Depertments List-(with AJAX)
		public function getDptList(Request $request) {
			$post = $request->all();
		    $idCont=$_POST['contId'];
		    
		    //$dptsListAjax = DB::table('departments')->where('countryId', $idCont)->where('is_active', 1)->get();

		    $cityListAjax = DB::table('cities')->where('countryId', $idCont)->where('cityStatus', 1)->get();

	        if($request->ajax()){
	            if(!$cityListAjax==""){
	            	
	                return response()->json([
	                'cityListAjax' => $cityListAjax
	                ]);
	            }  
	        }
		}


	//-- Get Cities List-(with AJAX)
		public function getCityList(Request $request) {
			$post = $request->all();
		    $idCITY=$_POST['citsIdA'];
		    $dptsListAjax = DB::table('departments')->where('cityID', $idCITY)->where('is_active', 1)->get();
	        if($request->ajax()){
	            if(!$dptsListAjax==""){
	                return response()->json([
	                'dptsListAjax' => $dptsListAjax
	                ]);
	            }else{
	            	$dptsListAjax[] = array(
					    'dptsListAjaxRes'  => 1
						);
	                return response()->json([
	                'dptsListAjax' => $dptsListAjax
	                ]);
	            }  
	        }
		}



	//-- Show Subcategories on click of main category-(with AJAX)
		public function useMyDet(Request $request) {
			$post = $request->all();

		    $userId=$post['userId'];
		    
		    $fillMyDetail = DB::table('users')->where('id', $userId)->where('status', 1)->get();

	        if($request->ajax()){

	            if(!$fillMyDetail==""){
	                return response()->json([
	                'fillMyDetail' => $fillMyDetail
	                ]);
	            }  
	        }
		}



	//-- Post Offer Last Step Submit-(Form Submit)
		public function postOfferSub(Request $request) {
			$post = $request->all();

			//----- Images -----//
					$files2 = Input::file('offerImg');
                    $files = array_filter($files2);
	                
	            	//-- foreach start --//
	                foreach ($files as $file) { 
	                	$destinationPath = 'uploads/postFiles/postImgs';
						$filenameOLD = $file->getClientOriginalName();
						$randomPostImage=rand(10,999999).time().rand(10,999999);
						$filename = $randomPostImage.$filenameOLD;
						$upload_success = $file->move($destinationPath, $filename);

						//-- Create An Array For Company Image Names
						$postImg[] = $filename;
	                }
	            	//-- Coma Seprated Post Image Names
	                	$postImgs=implode(',', $postImg);


       		//----- Certificates -----// 
	    
	            	//--Certificate 1
		            $crtDLchk= (isset($post['offerCrtDL']))? $post['offerCrtDL'] : "";
		            if($crtDLchk!==""){
		              $filesCDL1 = Input::file('offerCrtDL');
		              $destinationPath1 = 'uploads/postFiles/postCrts';
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
		            $crtPLchk= (isset($post['offerCrtPL']))? $post['offerCrtPL'] : "";
		            if($crtPLchk!==""){
		              $filesCPL1 = Input::file('offerCrtPL');
		              $destinationPathPL1 = 'uploads/postFiles/postCrts';
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
		            $crtFIchk= (isset($post['offerCrtFI']))? $post['offerCrtFI'] : "";
		            if($crtFIchk!==""){
		              $filesCFI1 = Input::file('offerCrtFI');
		              $destinationPathFI = 'uploads/postFiles/postCrts';
		              $filenameOLDCFI1 = $filesCFI1->getClientOriginalName();
		              $randomPostCrtFI=rand(10,999999).time().rand(10,999999);
		              $filenameCFI1 = $randomPostCrtFI.$filenameOLDCFI1;
		              $upload_successCFI = $filesCFI1->move($destinationPathFI, $filenameCFI1);
		              $certFI = $filenameCFI1;
		            }
		            else{
		                $certFI="";
		            }

			//----- Area Cover -----//
	            	$areaCover2= (isset($post['areaCov']))? $post['areaCov'] : "";
	            	if($areaCover2!==""){
	            		$areaCover1=$areaCover2;
						$areaCover=implode(',', $areaCover1);
	            	}else{
	            		$areaCover="";
	            	}
					

					if(!empty($post['droLicence'])) {
						$droLicence=$post['droLicence'];
					}else{
						$droLicence=0;
					}


					if(!empty($post['pilotLicence'])) {
						$pilotLicence=$post['pilotLicence'];
					}else{
						$pilotLicence=0;
					}


					if(!empty($post['flightInsurance'])) {
						$flightInsurance=$post['flightInsurance'];
					}else{
						$flightInsurance=0;
					}

					if(!empty($post['priceTxt'])){
						$priceTxt=$post['priceTxt'];
					}else{
						$priceTxt=0;
					}

					//----- Insert Query -----//	
					$data = array(
						'idUser' => Auth::user()->id,
						'roleUser' => Auth::user()->user_role,
	                    'categoryOffer' => $post['idMainCate'],
						'subCateOffer' => $post['idSubCate'],
						'videoOffer' => $post['offerVideo'],
						'titleOffer' => $post['offerTitle'],
						'deliveryTimeOffer' => $post['offerDlvTime'],
						'droneLicence' => $droLicence,
						'pilotLicence' => $pilotLicence,
						'insuranceFlight' => $flightInsurance,
						'descriptionOffer' => $post['offerDescrptn'],
						'priceOffer' => $post['offPrice'],
						'priceAmount' => $priceTxt,
						'currency' => $post['priceDrop'],
						'countryOffer' => $post['OfferCountry'],
						'deptOffer' => $post['OfferDept'],
						'cityOffer' => $post['OfferCity'],
						'streetOffer' => $post['OfferStreet'],
						//'mobOffer' => $post['OfferMob'],
						//'postCodeOffer' => $post['OfferPostCode'],
						'infoAdditOffer' => $post['OfferAddInfo'],
						'userMobOffer' => $post['offUserMob'],
						'userEmailOffer' => $post['offuserEmail'],
						'websiteOffer' => $post['offWebsite'],
						'personOffer' => $post['offPerson'],
						'confirmOff' => $post['confirmOff'],
						'areaCoverage' => $post['OfferDept'],
						'imgOffer' => $postImgs,
						'DLcertificate' => $certDL,
						'PLcertificate' => $certPL,
						'FIcertificate' => $certFI,
						'statusOffer' => 1,
	                );  
		            $postInsert = DB::table('offer')->insert($data);
		            $lastidPost = DB::getPdo()->lastInsertId();

		            //-- Query Check(Start)
		              if($postInsert>0) {
		                Session::put('postAddMsz', 'Su oferta ha sido publicada exitosamente');
		                return Redirect::to('/viewOffer/'.$lastidPost);
		              }
		              else{
		                Session::put('postAddMszErr', 'Por favor tratar De nuevo.');
		                return Redirect::to('/viewOffer/'.$lastidPost); 
		              }
		            //-- Query Check(End)

		}
#
#
}
