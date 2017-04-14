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



class editOfferController extends Controller {

	//-- View List Of Active Pilots On Frontend
		public function editOffF($id) {

			$offEdit = DB::table('offer')->where('idOffer', $id)->where('statusOffer', 1)->get();
			
			return view('postoffer/editOffer', [
			  'offEdit' => $offEdit,
			]);	

		}

	//-- Get Departments List Edit Offer-(with AJAX)
		public function dptListEditOff(Request $request) {
			$post = $request->all();
		    $idCont=$_POST['editCntIdA'];
		    
		    $dptListEdit = DB::table('departments')->where('countryId', $idCont)->where('is_active', 1)->get();
		    $cityListEdit = DB::table('cities')->where('countryId', $idCont)->where('cityStatus', 1)->get();

	        if($request->ajax()){
	            if(!$dptListEdit==""  || !$cityListEdit==""){
	            	
	                return response()->json([
	                'dptListEdit' => $dptListEdit,
	                'cityListEdit' => $cityListEdit
	                ]);
	            }  
	        }
		}

	//-- Get City List Edit Offer-(with AJAX)
		public function cityListEditOff(Request $request) {
			$post = $request->all();
		    $idCity=$_POST['editCityIdA'];
		    $dptsListResult = DB::table('departments')->where('cityID', $idCity)->where('is_active', 1)->get();
	        
	        if($request->ajax()){
	            if(!$dptsListResult==""){
	                return response()->json([
	                'dptsListResult' => $dptsListResult
	                ]);
	            }else{
	            	$dptsListResult[] = array(
					    'dptsListResultRes'  => 1
						);
	                return response()->json([
	                'dptsListResult' => $dptsListResult
	                ]);
	            }  
	        }
		}


	//-- Get City List Edit Offer-(with AJAX)
		public function editOfferSub(Request $request) {
			$post = $request->all();

			//-- Offer Id
		    	$offerID=(isset($post['offerIDHide']))? $post['offerIDHide'] : "";
		    
		    //---UPLOAD OFFER IMAGES----------(START)

				//-- Full Old Image String
				    $oldImgStrng=(isset($post['oldImgFull']))? $post['oldImgFull'] : "";

			    //-- Images To Del--(IN ARRAY)
				    $delImgsArr=(isset($post['delImgs']))? $post['delImgs'] : "";

			    //-- Convert Delete Image Name Array To Comma Seprated String
					if(!empty($delImgsArr)){
						//-- Images To Del--(IN COMMA SEPRATED STRING)
						$delImgsComma = implode (",", $delImgsArr);
			        }
			        else {
			        	$delImgsComma="";
			        }

			    //-- Get Remaining Old Images Srting From Full Image String
			        $imgsToDel = $delImgsComma;
			        $fullOldImgString=$oldImgStrng;

	                $imgsToDel1 = explode(',', $imgsToDel);
	                foreach ($imgsToDel1 as $imgsToDel2) {

	                    //-- Explode And Remove Selected Image From String
	                    $parts = explode(',', $fullOldImgString);

	                    while(($i = array_search($imgsToDel2, $parts)) !== false) {
	                        unset($parts[$i]);
	                    }
	                    $imgToDel= implode(',', $parts);
	                    $fullOldImgString=$imgToDel;
	                }
	                //-- Remaining Old Images
	                $remainOlds=$imgToDel;

		        //-- New Images
		            $chkNewImg=(isset($post['newimg']))? $post['newimg'] : "";
		            if($chkNewImg!==""){
		            	$files2 = Input::file('newimg');
		                $files = array_filter($files2);
	                	$newImg=array();
		                foreach ($files as $file) { 
		                	$destinationPath = 'uploads/postFiles/postImgs';
							$filenameOLD = $file->getClientOriginalName();
							$randomPostImage=rand(10,999999).time().rand(10,999999);
							$filename = $randomPostImage.$filenameOLD;
							$upload_success = $file->move($destinationPath, $filename);

							//-- Create An Array For Company Image Names
							$newImg[] = $filename;
		                }
			            $newImgs=implode(',', $newImg);
			        }else{
			        	$newImgs="";
			        }

		        //-- Combined New String
	                $newImgsL =$newImgs;
	                $remainOldsL =$remainOlds;
	                $fullNewString1 = $remainOldsL . "," . $newImgsL;
	                $fullNewString = trim($fullNewString1,",");
	                $images=$fullNewString;

		    //---UPLOAD OFFER IMAGES----------(END)
		    //
		    //
		    //
		    //---UPLOAD OFFER CERTIFICATES----------(START)
		    
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
                        $destinationPath1p = 'uploads/postFiles/postCrts';
                        $filenameOLDCDL1p = $filesCDL1p->getClientOriginalName();
                        $randomCrtDLp=rand(10,999999).time().rand(10,999999);
                        $filenameCDL1p = $randomCrtDLp.$filenameOLDCDL1p;
                        $upload_successCDLp = $filesCDL1p->move($destinationPath1p, $filenameCDL1p);
                        $CerDLfinal = $filenameCDL1p;
                    }
                    else{
                        $CerDLfinal="";
                    }
                }
                else{
                    $CerDLfinal=$oldCDL;
                }


                //--Crt 2
                if($oldCPL==""){
                    if($cPLnew!==""){
                        $filesCPL1p = Input::file('crtPLnew');
                        $destinationPath2p = 'uploads/postFiles/postCrts';
                        $filenameOLDCPL1p = $filesCPL1p->getClientOriginalName();
                        $randomCrtPLp=rand(10,999999).time().rand(10,999999);
                        $filenameCPL1p = $randomCrtPLp.$filenameOLDCPL1p;
                        $upload_successCDLp = $filesCPL1p->move($destinationPath2p, $filenameCPL1p);
                        $CerPLfinal = $filenameCPL1p;
                    }
                    else{
                        $CerPLfinal="";
                    }
                }
                else{
                    $CerPLfinal=$oldCPL;
                }


                //--Crt 2
                if($oldCFI==""){
                    if($cFInew!==""){
                        $filesCFI1p = Input::file('crtFInew');
                        $destinationPath3p = 'uploads/postFiles/postCrts';
                        $filenameOLDCFI1p = $filesCFI1p->getClientOriginalName();
                        $randomCrtFIp=rand(10,999999).time().rand(10,999999);
                        $filenameCFI1p = $randomCrtFIp.$filenameOLDCFI1p;
                        $upload_successCFIp = $filesCFI1p->move($destinationPath3p, $filenameCFI1p);
                        $CerFIfinal = $filenameCFI1p;
                    }
                    else{
                        $CerFIfinal="";
                    }
                }
                else{
                    $CerFIfinal=$oldCFI;
                }

			//---UPLOAD OFFER CERTIFICATES----------(END)
		    //
		    //
		    //----- Area Cover -----//
		    	$areaCover2=(isset($post['editOffAcover']))? $post['editOffAcover'] : "";
		    	if($areaCover2!==""){
					$areaCover1=$areaCover2;
					$areaCover=implode(',', $areaCover1);
				}else{
					$areaCover="";
				}
				
			//--Empty Conditions-(Drone Licence)
				if(!empty($post['droLicence'])) {
					$droLicence=$post['droLicence'];
				}else{
					$droLicence=0;
				}

			//--Empty Conditions-(Pilot Licence)
				if(!empty($post['pilotLicence'])) {
					$pilotLicence=$post['pilotLicence'];
				}else{
					$pilotLicence=0;
				}

			//--Empty Conditions-(Flight Insurance)
				if(!empty($post['flightInsurance'])) {
					$flightInsurance=$post['flightInsurance'];
				}else{
					$flightInsurance=0;
				}

			//--Empty Conditions-(price Text)
				if(!empty($post['priceTxt'])){
					$priceTxt=$post['priceTxt'];
				}else{
					$priceTxt=0;
				}

				$datas = array(
					'imgOffer' => $images,
					'DLcertificate' => $CerDLfinal,
					'PLcertificate' => $CerPLfinal,
					'FIcertificate' => $CerFIfinal,
					'videoOffer' => $post['editOffVid'],
					'titleOffer' => $post['editOffTitle'],
					'deliveryTimeOffer' => $post['offerDlvTime'],
					'droneLicence' => $droLicence,
					'pilotLicence' => $pilotLicence,
					'insuranceFlight' => $flightInsurance,
					'descriptionOffer' => $post['editOffDscrp'],
					'priceOffer' => $post['offPrice'],
					'currency' => $post['priceDrop'],
					'priceAmount' => $priceTxt,
					'countryOffer' => $post['cntOffEdt'],
					'cityOffer' => $post['cityOffEdt'],
					'deptOffer' => $post['dptOffEdt'],
					'streetOffer' => $post['streetOffEdit'],
					//'postCodeOffer' => $post['editOffPCode'],
					'infoAdditOffer' => $post['addInfoEditOff'],
					'userMobOffer' => $post['offUserMob'],
					'userEmailOffer' => $post['offuserEmail'],
					'websiteOffer' => $post['offWebsite'],
					'personOffer' => $post['offPerson'],
					'areaCoverage' => $post['dptOffEdt'],
				);

			$idOFFER=$offerID;
			$edtOFF = DB::table('offer')->where('idOffer', $idOFFER)->update($datas);

			if($edtOFF>0) {

				Session::put('edtOFFRmsz', 'Su oferta se actualiza con éxito.');
				return Redirect::to('viewOffer/'.$idOFFER);
			}
			else{
				Session::put('edtOFFRmszErr', 'Añade algunos cambios a la oferta de actualización.');
				return Redirect::to('viewOffer/'.$idOFFER);
			}
		}
#
#
}
