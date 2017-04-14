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
use Mail;

class viewOfferController extends Controller {

	//-- View List Of Added Offers In An Subcategory
		public function offerList($idMCat, $idSCat) {

			$sCatName = DB::table('subCategories')->where('idSubCategory', $idSCat)->get();

			$offerList = DB::table('offer')->where('categoryOffer', $idMCat)->where('subCateOffer', $idSCat)->where('statusOffer', 1)->get();
			
			return view('postoffer/offerList', [
			  'offerList' => $offerList,
			  'sCatName' => $sCatName,

			]);	
		}

	//-- View Offer Details---(Onclick Of View Offer)
		public function viewOffer($idOff) {
			$viewOffer = DB::table('offer')->where('idOffer', $idOff)->where('statusOffer', 1)->get();

			foreach($viewOffer as $viewOffers){

				//--Get Old Views Count
				$oldViewCount1=$viewOffers->viewCount;
				if($oldViewCount1!==""){
					$oldViewCount=$oldViewCount1;
				}else{
					$oldViewCount=0;
				}

				//--New Views Count
				$newViewCount=($oldViewCount+1);

				//--Update DB
				$dataVcount = array(
					'viewCount' => $newViewCount,
				);
				$viewUpd = DB::table('offer')->where('idOffer', $idOff)->update($dataVcount);

			}
			
			return view('postoffer/viewOffer', [
			  'viewOffer' => $viewOffer,
			]);	

		}

	//-- Add Or Update Offer Rating
		public function addOfferRating(Request $request) {

			$post = $request->all();
		    $idOffer=$post['offerIdAjax'];
		    $rateCount=$post['rateAjax'];
		    $userId=Auth::user()->id;

		    $rate = DB::table('offerRating')->where('userIdRateOffer', $userId)->where('offerIdRate', $idOffer)->get();

		    if(!empty($rate)) {
		    	
		    	//----- Update Query -----//
				$dataU = array(
					'userIdRateOffer' => $userId,
					'offerIdRate' => $idOffer,
					'countRateOffer' => $rateCount,
				);
				$rateUpd = DB::table('offerRating')->where('userIdRateOffer', $userId)->where('offerIdRate', $idOffer)->update($dataU);

				//-- Query Check(Start)
				if($request->ajax()){
		            if($rateUpd>0){
		            	$rate[]=$rateUpd;
		                return response()->json([
		                'rate' => $rate
		                ]);
		            }  
		        }
				//-- Query Check(End)	
		    }
		    else {
		    
		    	//----- Insert Query -----//
				$data = array(
                    'userIdRateOffer' => $userId,
					'offerIdRate' => $idOffer,
					'countRateOffer' => $rateCount,
					'statusRateOffer' => 1,
                );
	            $rateInsert = DB::table('offerRating')->insert($data); 	

	            //-- Query Check(Start)
				if($request->ajax()){
		            if($rateInsert>0) {

		            	$rate[]=$rateInsert;
		                return response()->json([
		                'rate' => $rate
		                ]);
		            }  
		        }
	            //-- Query Check(End)
		    }

		}

	//-- Add Or Update Offer Review
		public function postOffReview(Request $request) {
			$post = $request->all();

		    $txtOffRe=$post['txtReviewOff1'];
		    $idOffRe=$post['idOffRe1'];
		    $idUserRe=$post['idUserRe1'];

		    //--Check if review already added.
		    $revCHK= DB::table('offerReview')->where('userIdReviewOff', $idUserRe)->where('offerIdReviewOff', $idOffRe)->where('statusReviewOff', 1)->get();
		    $countRevCHK=count($revCHK);

		    //-- if Review Is Already Added By User
			if($countRevCHK!==0) {

				//-- Return With--(RevPathRes=1)-Error
				if($request->ajax()){
	            	$latestRe[] = array(
					    'RevPathRes'  => 2
						);
	                return response()->json([
	                'latestRe' => $latestRe
	                ]);
		        }

			} else {

			    //----- Insert Query -----//
				$dataReIns = array(
	                'userIdReviewOff' => $idUserRe,
					'offerIdReviewOff' => $idOffRe,
					'textReviewOff' => $txtOffRe,
					'statusReviewOff' => 1,
	            );
	            $reInsert = DB::table('offerReview')->insert($dataReIns); 	
	            if(!$reInsert=="") {

	            $latestRe = DB::table('offerReview')->where('userIdReviewOff', $idUserRe)->where('offerIdReviewOff', $idOffRe)->orderBy('idReviewOff', 'desc')->limit(1)->get();

	        		//-- Query Check(Start)
					if($request->ajax()){
						
			            if(!$latestRe=="") {
			                return response()->json([
			                'latestRe' => $latestRe
			                ]);
			            }  
			        }
		            //-- Query Check(End)   	
	            }

	        }
     
		}


	//-- Add Or Update Offer Rating
		public function getBarVal(Request $request) {
			$post = $request->all();
			$offerIdBar=$post['offerIdBarA'];
			$barR1 = DB::table('offerRating')->where('offerIdRate', $offerIdBar)->where('countRateOffer', 1)->get();
			$barR2 = DB::table('offerRating')->where('offerIdRate', $offerIdBar)->where('countRateOffer', 2)->get();
			$barR3 = DB::table('offerRating')->where('offerIdRate', $offerIdBar)->where('countRateOffer', 3)->get();
			$barR4 = DB::table('offerRating')->where('offerIdRate', $offerIdBar)->where('countRateOffer', 4)->get();
			$barR5 = DB::table('offerRating')->where('offerIdRate', $offerIdBar)->where('countRateOffer', 5)->get();
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



	//-- Add Or Update Offer Rating
		public function subQueryForm(Request $request) {
			$post = $request->all();
		    $queryTxt=$post['queryTxtAjax'];
		    $ownerId=$post['offOwnerAjax'];
		    $userEmail=Auth::user()->email;
		    $offOwner1 = DB::table('users')->where('id', $ownerId)->get();
		    $ownrFound=count($offOwner1);
		    if($ownrFound>0){
		    	foreach($offOwner1 as $offOwner){
		    		$emailOwner=$offOwner->email;
		    		if(($offOwner->user_role)==4){
		    			$ownrName=$offOwner->company_name;
		    		}else{
		    			$ownrName=$offOwner->fname." ".$offOwner->lname;
		    		}
		    		$data = array(
	                    'userMail' => $userEmail,
						'ownrName' => $ownrName,
						'ownrMail' => $emailOwner,
						'txt' => $queryTxt,
	                );
		    		Mail::send('emailView.offerQuery', ['data' => $data], function($message) use ($data)
		            {
		                $message->from($data['userMail'], "Quiero Un Drone");
		                $message->subject('Query From User');
		                $message->to($data['ownrMail']);
		            });
		    		if($request->ajax()){
			            if( count(Mail::failures()) > 0 ) {
			                $query[] = array(
			                  'FResult'  => '<p style="color: #F65B47;">Inténtalo de nuevo</p>'
			                );
							return response()->json([
								'query' => $query
							]);
			                
			            } else {
			                $query[] = array(
			                'FResult'  => '<p style="color: #F65B47;">Gracias por su consulta, nos pondremos en contacto con usted pronto.</p>',
			                );
							return response()->json([
								'query' => $query
							]);   
			            }
			        }
		    	}
		    }	 	
		}


	//-- Edit Review Form Submit
		public function editReSub(Request $request) {
			$post = $request->all();
		    $idReEdit=$post['idRe'];
		    $txtReEdit=$post['txtRe'];
		    $userIdRe=Auth::user()->id;
		    $ReEdit = array(
					'textReviewOff' => $txtReEdit,
				);
			$reUpd = DB::table('offerReview')->where('idReviewOff', $idReEdit)->where('userIdReviewOff', $userIdRe)->update($ReEdit);

			//-- Query Check(Start)
			if($request->ajax()){
	            if($reUpd>0){
					$reviewNew[] = array(
						'textReNew'  => $txtReEdit,
						'idReNew'  => $idReEdit
					);
	                return response()->json([
	                	'reviewNew' => $reviewNew
	                ]);
	            }  
	        }
			//-- Query Check(End)
		}


	//-- Edit Review Form Submit
		public function delReSub(Request $request) {
			$post = $request->all();
		    $idReDel=$post['idReDelA'];
		    $userIdRedel=Auth::user()->id;

		 //    $ReEdit = array(
			// 		'textReviewOff' => $txtReEdit,
			// 	);

			// $reUpd = DB::table('offerReview')->where('idReviewOff', $idReEdit)->where('userIdReviewOff', $userIdRe)->update($ReEdit);

			// //-- Query Check(Start)
			// if($request->ajax()){
	  //           if($reUpd>0){

   //              $reviewNew[] = array(
			// 	    'textReNew'  => $txtReEdit,
			// 	    'idReNew'  => $idReEdit
			// 		);

   //              return response()->json([
   //              'reviewNew' => $reviewNew
   //              ]);

	  //           }  
	  //       }
			// //-- Query Check(End)
			$redel=DB::table('offerReview')->where('idReviewOff', $idReDel)->where('userIdReviewOff', $userIdRedel)->delete();

	        //-- Query Check(Start)
			if($request->ajax()){
	            if($redel!=="") {
					$reDelResult[] = array(
						'statusReDel'  => '1'
					);
					return response()->json([
						'reDelResult' => $reDelResult
					]);
	            }
	            else{
					$reDelResult[] = array(
						'statusReDel'  => '0'
					);
					return response()->json([
						'reDelResult' => $reDelResult
					]);
	            } 
	        }
			//-- Query Check(End)
		}


	//-- Login To Add Review
		public function offReLogin($id) {
			return Redirect::to('viewOffer/'.$id);
		}

	//-- Login To Interest
		public function offInterestLogin($id) {
			return Redirect::to('viewOffer/'.$id);
		}

	//-- View more before login
		public function viewMoreOffer($ofrId) {
			return Redirect::to('viewOffer/'.$ofrId);
		}

}

