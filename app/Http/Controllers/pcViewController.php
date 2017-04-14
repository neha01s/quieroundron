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



class pcViewController extends Controller {
#
#
#
//-- Pilot controller Code--(Start)

	//-- View List Of Active Pilots On Frontend
		public function pilotList() {

		    $pList = DB::table('users')->where('user_role', 3)->where('status', 1)->orderBy('fname', 'ASC')->get();
        	return view('viewPC/pilotList', [
					  'pList' => $pList,
					]);   
		}


	//-- View Selected Pilots Profile
		public function pilotView($idP) {

		    $pView = DB::table('users')->where('id', $idP)->where('user_role', 3)->where('status', 1)->get();
        	return view('viewPC/viewPilot', [
					  'pView' => $pView,
					]);   
		}


	//-- Add Rating Pilot
		public function addRatePil(Request $request) {
			$post = $request->all();
		    $idU=$_POST['uID'];
		    $idPil=$_POST['pID'];
		    $countRate=$_POST['rCount'];
		    
		    $rateCHK= DB::table('pilotRating')->where('userIdPRate', $idU)->where('pilotIdPRate', $idPil)->get();
		    if(!empty($rateCHK)) {
		    	
		    	$dataRateU = array(
	                'userIdPRate' => $idU,
					'pilotIdPRate' => $idPil,
					'countPRate' => $countRate,
					'statusPRate' => 1,
	            );
	            $rateUpd = DB::table('pilotRating')->where('userIdPRate', $idU)->where('pilotIdPRate', $idPil)->update($dataRateU);

	            if($request->ajax()){
		            if($rateUpd>0) {
		            	$rateRes[] = array(
						    'rateSuccess'  => 1
							);
		                return response()->json([
		                'rateRes' => $rateRes
		                ]);
		            }
		            else{
		            	$rateRes[] = array(
						    'rateSuccess'  => 0
							);
		                return response()->json([
		                'rateRes' => $rateRes
		                ]);
		            }
		        }
		    }
		    else {

				$dataRate = array(
	                'userIdPRate' => $idU,
					'pilotIdPRate' => $idPil,
					'countPRate' => $countRate,
					'statusPRate' => 1,
	            );
	            $rateInsert = DB::table('pilotRating')->insert($dataRate); 	

				if($request->ajax()){
		            if($rateInsert>0) {
		            	$rateRes[] = array(
						    'rateSuccess'  => 1
							);
		                return response()->json([
		                'rateRes' => $rateRes
		                ]);
		            }
		            else{
		            	$rateRes[] = array(
						    'rateSuccess'  => 0
							);
		                return response()->json([
		                'rateRes' => $rateRes
		                ]);
		            }
		        }
			}
		}


	//-- Add Review Pilot
		public function addReviewPil(Request $request) {
			$post = $request->all();
		    $idUre=$_POST['uIDRE'];
		    $idPilre=$_POST['pIDRE'];
		    $txtRev=$_POST['reTEXT'];

		    $revCHK= DB::table('pilotReview')->where('userIdReviewP', $idUre)->where('pilotIdReviewP', $idPilre)->where('statusReviewP', 1)->get();
		    $countRevCHK=count($revCHK);

		    //-- if Review Is Already Added By User
			if($countRevCHK!==0) {

				//-- Return With--(RevPathRes=1)-Error
				if($request->ajax()){
	            	$revRes[] = array(
					    'RevPathRes'  => 1
						);
	                return response()->json([
	                'revRes' => $revRes
	                ]);
		        }

			}
			else { //-- if User Is Going To Add Review First Time
			
			//--Get Rating Given By User
				$getRateRev1= DB::table('pilotRating')->where('userIdPRate', $idUre)->where('pilotIdPRate', $idPilre)->get();
				foreach($getRateRev1 as $getRateRev){
					$ratingOnPil=$getRateRev->countPRate;
				}

			//--Get User Name--(Review Posted By)
				$roleReUser=Auth::user()->user_role;
				if($roleReUser==4){
					$reUserName=Auth::user()->company_name;
				}else{
					$reUserName=Auth::user()->fname." ".Auth::user()->lname;
				}

			//-- Insert Review
				$dataRevs = array(
	                'userIdReviewP' => $idUre,
					'pilotIdReviewP' => $idPilre,
					'textReviewP' => $txtRev,
					'statusReviewP' => 1,
	            );
	            $revInsert = DB::table('pilotReview')->insert($dataRevs); 	

				if($request->ajax()){
		            if($revInsert>0) {

		            	//--Get Timestamp Of Posted Review
		            	$lastid = DB::getPdo()->lastInsertId();
		            	$retimestamp1= DB::table('pilotReview')->where('idReviewP', $lastid)->get();

		            	foreach($retimestamp1 as $retimestamp){
		            		$revDate=$retimestamp->dateReviewP;
		            	}

		            	//-- Return With--(RevPathRes=3)-Success
		            	$revRes[] = array(
						    'userIdReSucc'  => $idUre,
						    'pilIdReSucc'  => $idPilre,
						    'txtReSucc'  => $txtRev,
						    'rateReSucc'  => $ratingOnPil,
						    'uNameReSucc'  => $reUserName,
						    'dateReSucc'  => $revDate,
						    'RevPathRes'  => 3,
							);
		                return response()->json([
		                'revRes' => $revRes
		                ]);
		            }
		            else{
		            	//-- Return With--(RevPathRes=2)-Error
		            	$revRes[] = array(
						    'RevPathRes'  => 2
							);
		                return response()->json([
		                'revRes' => $revRes
		                ]);
		            }
		        }
			}
		}


	//-- Update Bar Rating Pilot --(With Ajax)
		public function getBarpilV(Request $request) {
			$post = $request->all();
			$pilIdBar=$post['pilIdBarA'];
			$barR1 = DB::table('pilotRating')->where('pilotIdPRate', $pilIdBar)->where('countPRate', 1)->get();
			$barR2 = DB::table('pilotRating')->where('pilotIdPRate', $pilIdBar)->where('countPRate', 2)->get();
			$barR3 = DB::table('pilotRating')->where('pilotIdPRate', $pilIdBar)->where('countPRate', 3)->get();
			$barR4 = DB::table('pilotRating')->where('pilotIdPRate', $pilIdBar)->where('countPRate', 4)->get();
			$barR5 = DB::table('pilotRating')->where('pilotIdPRate', $pilIdBar)->where('countPRate', 5)->get();
			$count1=count($barR1);
			$count2=count($barR2);
			$count3=count($barR3);
			$count4=count($barR4);
			$count5=count($barR5);

			if($count1!==0) {
				$oneRate = $count1;
			}else{
				$oneRate = "0";
			}

			if($count2!==0) {
				$twoRate = $count2;
			}else{
				$twoRate = "0";
			}

			if($count3!==0) {
				$threeRate = $count3;
			}else{
				$threeRate = "0";
			}

			if($count4!==0) {
				$fourRate = $count4;
			}else{
				$fourRate = "0";
			}

			if($count5!==0) {
				$fiveRate = $count5;
			}else{
				$fiveRate = "0";
			}

 			///-- Total People Who Gave Rating
 				$rowsCount=($oneRate+$twoRate+$threeRate+$fourRate+$fiveRate);

 			//-- Full Stars Count
 				$totalRate=($rowsCount*5);

 			//-- Got Stars Count
 				$gotRate=(($oneRate*1)+($twoRate*2)+($threeRate*3)+($fourRate*4)+($fiveRate*5));

 			//-- one Stars %age Count
 				$oneprsnt=($oneRate*100)/($rowsCount);

 			//-- two Stars %age Count
 				$twoprsnt=($twoRate*100)/($rowsCount);

 			//-- three Stars %age Count
 				$threeprsnt=($threeRate*100)/($rowsCount);

 			//-- four Stars %age Count
 				$fourprsnt=($fourRate*100)/($rowsCount);

 			//-- five Stars %age Count
 				$fiveprsnt=($fiveRate*100)/($rowsCount);

 			//-- Overall %age
 				$overAllP=($gotRate*100)/($totalRate);

 			//-- Overall %age out of five
 				$overAllFP=(5*$overAllP)/(100);

			$barVals[] = array(
					    'peopleCount'  => $rowsCount,
					    'totalStars'  => $totalRate,
					    'gotStars' => $gotRate,
					    'oneStars' => $oneRate,
					    'twoStars' => $twoRate,
					    'threeStars'  => $threeRate,
					    'fourStars'  => $fourRate,
					    'fiveStars' => $fiveRate,
					    'oneP' => $oneprsnt,
					    'twoP' => $twoprsnt,
					    'threeP'  => $threeprsnt,
					    'fourP'  => $fourprsnt,
					    'fiveP' => $fiveprsnt,
					    'overallPrsntF' => $overAllFP
						);
			return response()->json([
	            'barVals' => $barVals
	            ]);

		}

	
	//-- Edit Review--(On Pilot Profile)  
		public function editRePil(Request $request) {
			$post = $request->all();
			$reviewIdP=$post['reIDpil'];
			$reviewTextP=$post['txtEditReA'];
			$dataReu = array(
                'textReviewP' => $reviewTextP,
            );
            $revUp = DB::table('pilotReview')->where('idReviewP', $reviewIdP)->update($dataReu);

            if($request->ajax()){
            	$revUpRes[] = array(
				    'editRevPath'  => 1,
				    'editRevTxt'  => $reviewTextP,
					);
                return response()->json([
                'revUpRes' => $revUpRes
                ]);
	        }
		}


	//-- Delete Review--(On Pilot Profile)  
		public function delRePil(Request $request) {
			$post = $request->all();
			$delRevIdP=$post['revIdDel'];
            $revDel = DB::table('pilotReview')->where('idReviewP', $delRevIdP)->delete();
            if($request->ajax()){
            	if($revDel!=="") {
            		$revDelRes[] = array(
				    'delRevPath'  => 1,
					);
	                return response()->json([
	                'revDelRes' => $revDelRes
	                ]);
            	}else{
            		$revDelRes[] = array(
				    'delRevPath'  => 0,
					);
	                return response()->json([
	                'revDelRes' => $revDelRes
	                ]);
            	}
	        }
		}


	//-- Login To Add Review
		public function viewPilLogin($id) {
			return Redirect::to('pilotView/'.$id);
		}


	//-- Add Pilot To Favaurite
		public function addPiltoFv(Request $request) {
			$post = $request->all();
			$fvPilId=$post['pilIdFv'];
			$fvUsrId=$post['usrIdFv'];

			$chkFvAddP = DB::table('fvrtPilot')->where('idpFVP', $fvPilId)->where('iduFVP', $fvUsrId)->get();
			$chkFvAddPcount=count($chkFvAddP);
			if($chkFvAddPcount==0){

				$dataFvP = array(
                'idpFVP' => $fvPilId,
				'iduFVP' => $fvUsrId,
	            );
	            $fvInsrtP = DB::table('fvrtPilot')->insert($dataFvP); 	

				if($request->ajax()){
		            if($fvInsrtP>0) {
		            	$fvResP[] = array(
					    	'fvPpath'  => 1,
						);
		                return response()->json([
		                'fvResP' => $fvResP
		                ]);

		        	} else {
		        		$fvResP[] = array(
					   		'fvPpath'  => 0,
						);
		                return response()->json([
		                'fvResP' => $fvResP
		                ]);
		        	}
		        }
			}else{

				if($request->ajax()){
					$fvResP[] = array(
						'fvPpath'  => 1,
					);
	                return response()->json([
	                	'fvResP' => $fvResP
	                ]);
		        }
			}
		}



	//-- Remove Pilot From Favaurite
		public function rmvPilfrmFv(Request $request) {
			$post = $request->all();
			$fvPilIdrmv=$post['pilIdFvRmv'];
			$fvUsrIdrmv=$post['usrIdFvRmv'];
	
            $fvDelP = DB::table('fvrtPilot')->where('idpFVP', $fvPilIdrmv)->where('iduFVP', $fvUsrIdrmv)->delete();

			if($request->ajax()){
	            if($fvDelP>0) {

	            	$rmvfvResP[] = array(
				    	'rmvFvPpath'  => 1,
					);
	                return response()->json([
	                'rmvfvResP' => $rmvfvResP
	                ]);

	        	}else {

	        		$rmvfvResP[] = array(
				   		'rmvFvPpath'  => 0,
					);
	                return response()->json([
	                'rmvfvResP' => $rmvfvResP
	                ]);

	        	}
	        }
		}


//-- Pilot controller Code--(End)
#
#
#
//-- Company controller Code--(Start)

	//-- View List Of Active Pilots On Frontend
		public function compList() {

		    $cList = DB::table('users')->where('user_role', 4)->where('status', 1)->orderBy('company_name', 'ASC')->get();
        	return view('viewPC/compList', [
					  'cList' => $cList,
					]);
		}

	//-- View Selected Company Profile
		public function compView($idC) {

		    $cView = DB::table('users')->where('id', $idC)->where('user_role', 4)->where('status', 1)->get();
        	return view('viewPC/viewComp', [
					  'cView' => $cView,
					]);
		}

	//-- Add Rating Company
		public function addRateCmp(Request $request) {
			$post = $request->all();
		    $idUcmp=$_POST['uIDcmp'];
		    $idcmp=$_POST['IDCmp'];
		    $countRateCmp=$_POST['rCountCmp'];
		    
		    $rateCHKcmp= DB::table('compRating')->where('userIdCRate', $idUcmp)->where('compIdCRate', $idcmp)->get();
		    if(!empty($rateCHKcmp)) {
		    	
		    	$dataRateUcmp = array(
	                'userIdCRate' => $idUcmp,
					'compIdCRate' => $idcmp,
					'countCRate' => $countRateCmp,
					'statusCRate' => 1,
	            );
	            $rateUpdcmp = DB::table('compRating')->where('userIdCRate', $idUcmp)->where('compIdCRate', $idcmp)->update($dataRateUcmp);

	            if($request->ajax()){
		            if($rateUpdcmp>0) {
		            	$rateResCmp[] = array(
						    'rateSuccessC'  => 1
							);
		                return response()->json([
		                'rateResCmp' => $rateResCmp
		                ]);
		            }
		            else{
		            	$rateResCmp[] = array(
						    'rateSuccessC'  => 0
							);
		                return response()->json([
		                'rateResCmp' => $rateResCmp
		                ]);
		            }
		        }
		    }
		    else {

				$dataRateC = array(
	                'userIdCRate' => $idUcmp,
					'compIdCRate' => $idcmp,
					'countCRate' => $countRateCmp,
					'statusCRate' => 1,
	            );
	            $rateInsertC = DB::table('compRating')->insert($dataRateC); 	

				if($request->ajax()){
		            if($rateInsertC>0) {
		            	$rateResCmp[] = array(
						    'rateSuccessC'  => 1
							);
		                return response()->json([
		                'rateResCmp' => $rateResCmp
		                ]);
		            }
		            else{
		            	$rateResCmp[] = array(
						    'rateSuccessC'  => 0
							);
		                return response()->json([
		                'rateResCmp' => $rateResCmp
		                ]);
		            }
		        }
			}
		}


	//-- Add Review Company
		public function addReviewCmp(Request $request) {
			$post = $request->all();
		    $idUreCmp=$_POST['uIDREcmp'];
		    $idCmpRe=$_POST['IDREcmp'];
		    $txtRevCmp=$_POST['reTEXTcmp'];

		    $revCHKcmp= DB::table('compReview')->where('userIdReviewC', $idUreCmp)->where('compIdReviewC', $idCmpRe)->where('statusReviewC', 1)->get();
		    $countRevCHKcmp=count($revCHKcmp);

		    //-- if Review Is Already Added By User
			if($countRevCHKcmp!==0) {

				//-- Return With--(RevPathResC=1)-Error
				if($request->ajax()){
	            	$revResC[] = array(
					    'RevPathResC'  => 1
						);
	                return response()->json([
	                'revResC' => $revResC
	                ]);
		        }
			}
			else { //-- if User Is Going To Add Review First Time
			
			//--Get Rating Given By User
				$getRateRevC1= DB::table('compRating')->where('userIdCRate', $idUreCmp)->where('compIdCRate', $idCmpRe)->get();
				foreach($getRateRevC1 as $getRateRevC){
					$ratingOnCmp=$getRateRevC->countCRate;
				}

			//--Get User Name--(Review Posted By)
				$roleReUser=Auth::user()->user_role;
				if($roleReUser==4){
					$reUserName=Auth::user()->company_name;
				}else{
					$reUserName=Auth::user()->fname." ".Auth::user()->lname;
				}


			//-- Insert Review
				$dataRevs = array(
	                'userIdReviewC' => $idUreCmp,
					'compIdReviewC' => $idCmpRe,
					'textReviewC' => $txtRevCmp,
					'statusReviewC' => 1,
	            );
	            $revInsert = DB::table('compReview')->insert($dataRevs); 	

				if($request->ajax()){
		            if($revInsert>0) {

		            	//--Get Timestamp Of Posted Review
		            	$lastidC = DB::getPdo()->lastInsertId();
		            	$retimestamp1= DB::table('compReview')->where('idReviewC', $lastidC)->get();

		            	foreach($retimestamp1 as $retimestamp){
		            		$revDate=$retimestamp->dateReviewC;
		            	}

		            	//-- Return With--(RevPathResC=3)-Success
		            	$revResC[] = array(
						    'CuserIdReSucc'  => $idUreCmp,
						    'CmpIdReSucc'  => $idCmpRe,
						    'CtxtReSucc'  => $txtRevCmp,
						    'CrateReSucc'  => $ratingOnCmp,
						    'CuNameReSucc'  => $reUserName,
						    'CdateReSucc'  => $revDate,
						    'RevPathResC'  => 3,
							);
		                return response()->json([
		                'revResC' => $revResC
		                ]);
		            }
		            else{
		            	//-- Return With--(RevPathResC=2)-Error
		            	$revResC[] = array(
						    'RevPathResC'  => 2
							);
		                return response()->json([
		                'revResC' => $revResC
		                ]);
		            }
		        }
			}
		}


	//-- Update Bar Rating Company --(With Ajax)
		public function getBarCmpV(Request $request) {
			$post = $request->all();
			$compIdBar=$post['cmpIdBarA'];
			$barR1 = DB::table('compRating')->where('compIdCRate', $compIdBar)->where('countCRate', 1)->get();
			$barR2 = DB::table('compRating')->where('compIdCRate', $compIdBar)->where('countCRate', 2)->get();
			$barR3 = DB::table('compRating')->where('compIdCRate', $compIdBar)->where('countCRate', 3)->get();
			$barR4 = DB::table('compRating')->where('compIdCRate', $compIdBar)->where('countCRate', 4)->get();
			$barR5 = DB::table('compRating')->where('compIdCRate', $compIdBar)->where('countCRate', 5)->get();
			$count1=count($barR1);
			$count2=count($barR2);
			$count3=count($barR3);
			$count4=count($barR4);
			$count5=count($barR5);

			if($count1!==0) {
				$oneRate = $count1;
			}else{
				$oneRate = "0";
			}

			if($count2!==0) {
				$twoRate = $count2;
			}else{
				$twoRate = "0";
			}

			if($count3!==0) {
				$threeRate = $count3;
			}else{
				$threeRate = "0";
			}

			if($count4!==0) {
				$fourRate = $count4;
			}else{
				$fourRate = "0";
			}

			if($count5!==0) {
				$fiveRate = $count5;
			}else{
				$fiveRate = "0";
			}

 			///-- Total People Who Gave Rating
 				$rowsCount=($oneRate+$twoRate+$threeRate+$fourRate+$fiveRate);

 			//-- Full Stars Count
 				$totalRate=($rowsCount*5);

 			//-- Got Stars Count
 				$gotRate=(($oneRate*1)+($twoRate*2)+($threeRate*3)+($fourRate*4)+($fiveRate*5));

 			//-- one Stars %age Count
 				$oneprsnt=($oneRate*100)/($rowsCount);

 			//-- two Stars %age Count
 				$twoprsnt=($twoRate*100)/($rowsCount);

 			//-- three Stars %age Count
 				$threeprsnt=($threeRate*100)/($rowsCount);

 			//-- four Stars %age Count
 				$fourprsnt=($fourRate*100)/($rowsCount);

 			//-- five Stars %age Count
 				$fiveprsnt=($fiveRate*100)/($rowsCount);

 			//-- Overall %age
 				$overAllP=($gotRate*100)/($totalRate);

 			//-- Overall %age out of five
 				$overAllFP=(5*$overAllP)/(100);

			$barValsC[] = array(
					    'peopleCount'  => $rowsCount,
					    'totalStars'  => $totalRate,
					    'gotStars' => $gotRate,
					    'oneStars' => $oneRate,
					    'twoStars' => $twoRate,
					    'threeStars'  => $threeRate,
					    'fourStars'  => $fourRate,
					    'fiveStars' => $fiveRate,
					    'oneP' => $oneprsnt,
					    'twoP' => $twoprsnt,
					    'threeP'  => $threeprsnt,
					    'fourP'  => $fourprsnt,
					    'fiveP' => $fiveprsnt,
					    'overallPrsntF' => $overAllFP
						);

			return response()->json([
	            'barValsC' => $barValsC
	            ]);
		}


	//-- Edit Review--(On Company Profile)  
		public function editReCompy(Request $request) {
			$post = $request->all();
			$reviewIdC=$post['reIDcomp'];
			$reviewTextC=$post['txtEditReAcomp'];
			$dataReuC = array(
                'textReviewC' => $reviewTextC,
            );
            $revUp = DB::table('compReview')->where('idReviewC', $reviewIdC)->update($dataReuC);

            if($request->ajax()){
            	$revUpResCmp[] = array(
				    'editRevPathC'  => 1,
				    'editRevTxtC'  => $reviewTextC,

					);
                return response()->json([
                'revUpResCmp' => $revUpResCmp
                ]);
	        }
		}


	//-- Delete Review--(On Company Profile)  
		public function delReCompy(Request $request) {
			$post = $request->all();
			$delRevIdC=$post['revIdDelCmp'];
            $revDel = DB::table('compReview')->where('idReviewC', $delRevIdC)->delete();
            if($request->ajax()){
            	if($revDel!=="") {
            		$revDelResC[] = array(
				    'delRevPathC'  => 1,
					);
	                return response()->json([
	                'revDelResC' => $revDelResC
	                ]);
            	}else{
            		$revDelResC[] = array(
				    'delRevPathC'  => 0,
					);
	                return response()->json([
	                'revDelResC' => $revDelResC
	                ]);
            	}
	        }
		}


	//-- Login To Add Review
		public function viewCmpLogin($id) {
			return Redirect::to('compView/'.$id);
		}


	//-- Add Company To Favaurite
		public function addCmptoFv(Request $request) {
			$post = $request->all();
			$fvCmpId=$post['cmpIdFvC'];
			$fvUsrIdC=$post['usrIdFvC'];

			$chkFvAddC = DB::table('fvrtCompany')->where('idcFVC', $fvCmpId)->where('iduFVC', $fvUsrIdC)->get();
			$chkFvAddCcount=count($chkFvAddC);
			if($chkFvAddCcount==0){

				$dataFvP = array(
                'idcFVC' => $fvCmpId,
				'iduFVC' => $fvUsrIdC,
	            );
	            $fvInsrtC = DB::table('fvrtCompany')->insert($dataFvP); 	

				if($request->ajax()){

		            if($fvInsrtC>0) {
		            	$fvResC[] = array(
					    	'fvCpath'  => 1,
						);
		                return response()->json([
		                'fvResC' => $fvResC
		                ]);

		        	} else {
		        		$fvResC[] = array(
					   		'fvCpath'  => 0,
						);
		                return response()->json([
		                'fvResC' => $fvResC
		                ]);
		        	}
		        }
			}else{

				if($request->ajax()){
					$fvResC[] = array(
						'fvCpath'  => 1,
					);
	                return response()->json([
	                	'fvResC' => $fvResC
	                ]);
		        }
			}
		}


	//-- Remove Company From Favaurite
		public function rmvCmpfrmFv(Request $request) {
			$post = $request->all();
			$fvCmpIdrmv=$post['cmpIdFvRmv'];
			$fvUsrIdrmvC=$post['usrIdFvRmvC'];
	
            $fvDelC = DB::table('fvrtCompany')->where('idcFVC', $fvCmpIdrmv)->where('iduFVC', $fvUsrIdrmvC)->delete();

			if($request->ajax()){
	            if($fvDelC>0) {

	            	$rmvfvResC[] = array(
				    	'rmvFvCpath'  => 1,
					);
	                return response()->json([
	                'rmvfvResC' => $rmvfvResC
	                ]);

	        	}else {

	        		$rmvfvResC[] = array(
				   		'rmvFvCpath'  => 0,
					);
	                return response()->json([
	                'rmvfvResC' => $rmvfvResC
	                ]);

	        	}
	        }
		}

//-- Company controller Code--(End)
#
#
}




