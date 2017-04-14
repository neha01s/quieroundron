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



class searchController extends Controller {

	//-- View Search Results
		public function srchRslt($idStatic) {

			$allOfs1 = DB::table('offer')->where('categoryOffer', $idStatic)->where('statusOffer', 1)->get();
			$getMainC1 = DB::table('mainCategories')->where('idMainCategory', $idStatic)->where('statusMainCategory', 1)->get();

			return view('search/srchRslt', [
			  'allOfs1' => $allOfs1,
			  'getMainC1' => $getMainC1,
			]);	
		}


	//-- Search Sub-Category Filter --(View Page)
		public function sFilter() {
			return view('search/sFilter');	
		}

	//-- Search Sub-Category Filter --(Get Offers In Selected SubCategories)
		public function sFilter1($id, $idMain) {	
			return Redirect::to('/sFilter?str='.$id.'&ctr='.$idMain); 
		}

	//-- Search Area Coverage Filter--(Get Offers In Selected Area)
		public function aCovFilter1($commaIdOff, $mCat) {	
			return Redirect::to('/aCovFilter?stg='.$commaIdOff.'&ctr='.$mCat); 
		}

	//-- Search Area Coverage Filter--(View Page)
		public function aCovFilter() {
			return view('search/aCovFilter');	
		}

	//-- Price Filter--(Main Category Price View)
		public function priceMainS() {
			return view('search/priceMainS');	
		}

	//-- Price Filter--(Main Category Price Redirect)
		public function priceMainS1(Request $request) {
			$post = $request->all();
		    $MSmin=$post['minPrice'];
		    $MSmax=$post['maxPrice'];
		    $MSmainCat=$post['mCatHide'];
		    return Redirect::to('/priceMainS?mx='.$MSmax.'&mct='.$MSmainCat.'&mn='.$MSmin);
 
		}

	//-- Price Filter--(Sub Category Price View)
		public function priceSubS() {
			return view('search/priceSubS');	
		}

	//-- Price Filter--(Sub Category Price Redirect)
		public function priceSubS1(Request $request) {
			$post = $request->all();
		    $MSmin=$post['minPrice'];
		    $MSmax=$post['maxPrice'];
		    $MSmainCat=$post['mCatHide'];
		    $MSsubCat=$post['sCatHide'];
		    return Redirect::to('/priceSubS?mx='.$MSmax.'&mct='.$MSmainCat.'&mn='.$MSmin.'&sct='.$MSsubCat);
		}





	//-- Price Filter--(Area Cover Price View)
		public function priceAcovS() {
			return view('search/priceAcovS');	
		}

	//-- Price Filter--(Area Cover Price Redirect)
		public function priceAcovS1(Request $request) {
			$post = $request->all();
		    $ACmin=$post['minPrice'];
		    $ACmax=$post['maxPrice'];
		    $ACmainCat=$post['McatAcovHide'];
		    $ACcomma=$post['commaAcovHide'];
		    return Redirect::to('/priceAcovS?mx='.$ACmax.'&mct='.$ACmainCat.'&mn='.$ACmin.'&spd='.$ACcomma);
		}

#
#
}
