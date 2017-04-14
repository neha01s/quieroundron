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



class offManageController extends Controller {

      //-- View List Of Offers
            public function offManageView() {

                  $UserOffer = DB::table('offer')->where('roleUser', 1)->get();
                  $pilOffer = DB::table('offer')->where('roleUser', 3)->get();
                  $cmpOffer = DB::table('offer')->where('roleUser', 4)->get();


                  return view('admin/offer/offListView', [
                    'UserOffer' => $UserOffer,
                    'pilOffer' => $pilOffer,
                    'cmpOffer' => $cmpOffer,
                  ]);

            }


      //-- Disable Added Offer
            public function disOfferAdmin($id, $role, $idUser) {
                  $datas = array(
                  'statusOffer' => 0,

                  );

                  $disOff = DB::table('offer')->where('idOffer', $id)->update($datas);

                  if($disOff!=="") {

                        Session::put('offMsz', 'Offer Is Disabled Successfully.');
                        return Redirect::to('admin/offManageView?case='.$role);
                  }
            }

      //-- Enable Added Offer
            public function enblOfferAdmin($id, $role, $idUser) {
                  $datas2 = array(
                  'statusOffer' => 1,

                  );

                  $enblOff = DB::table('offer')->where('idOffer', $id)->update($datas2);

                  if($enblOff!=="") {
                        Session::put('offMsz', 'Offer Is Enabled Successfully.');
                        return Redirect::to('admin/offManageView?case='.$role);
                  }
            }


      //-- View Added Offer
            public function viewOfferAdmin($id, $role, $idUser) {

                  if($role==1){
                        $viewSngUserOff = DB::table('offer')->where('idOffer', $id)->where('idUser', $idUser)->where('roleUser', 1)->get();
                        return view('admin/offer/userOffView', [
                         'viewSngUserOff' => $viewSngUserOff,
                        ]);
                  }

                  if($role==3){
                        $viewSngPilOff = DB::table('offer')->where('idOffer', $id)->where('idUser', $idUser)->where('roleUser', 3)->get();
                        return view('admin/offer/pilOffView', [
                          'viewSngPilOff' => $viewSngPilOff,
                         
                        ]);
                  }

                  if($role==4){
                        $viewSngCmpOff = DB::table('offer')->where('idOffer', $id)->where('idUser', $idUser)->where('roleUser', 4)->get();
                        return view('admin/offer/cmpOffView', [
                          'viewSngCmpOff' => $viewSngCmpOff,
                        ]);
                  }

            }

}
