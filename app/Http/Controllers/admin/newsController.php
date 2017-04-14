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



class newsController extends Controller {

      //-- View News Panel
        public function newsDefault() {
            return Redirect::to('admin/news');
        }



      //-- View News Panel (Default case)
        public function news(Request $request) {

          $newsView = DB::table('news')->orderBy('id_news', 'ASC')->get();
          return view('admin/news', [
              'newsView' => $newsView,
          ]);
        }



      //-- View News Panel (View Add news Form)
        public function addNews() {
          return Redirect::to('admin/news?urlCase=add');
        }



      //-- News Panel (Submit Add news Form)
        public function addNewsSub(Request $request) {

           //-- Image Validations(start)
              $file1 = Input::file('newsImgAdd');
        
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
                Session::put('addNewsImgMsz', $abcd);
                return Redirect::to('admin/news?urlCase=add');
                   
              }
              else{

              $Path1=1;
          
              }
            //-- Image Validations(end)

            //-- Image Upload If Validation Pass(start)
              if($Path1==1) {

                $destinationPath1 = 'uploads/imagesNews';
                $filenameOLD = $file1->getClientOriginalName();
                $randomRegisImage=rand(10,999999).time().rand(10,999999);
                $filename1 = $randomRegisImage.$filenameOLD;
                $upload_succes = $file1->move($destinationPath1, $filename1);

                //-- Add Cms Page Code(Start)
                if(!$upload_succes="") {

                    $data = array(
                                  'title_news' => $request['news_title'],
                                  'content_news' => $request['news_content'],
                                  'image_news' => $filename1,
                                  'status_news' => '1',
                                  );
                      
                    $i = DB::table('news')->insert($data); 


                    //-- Query Check(Start)
                      if($i!=="") {
                        Session::put('newsAddMsz', 'News Added Successfully');
                        return Redirect::to('admin/news');
                      }
                      else{
                        Session::put('newsAddMsz', 'News Not Added');
                        return Redirect::to('admin/news'); 
                      }
                    //-- Query Check(End)

                }
                //-- Add Cms Page Code(End)

              }
              //-- Image Upload If Validation Pass(End)
        }




      //-- News Panel (Submit Delete-news Form)
        public function delNewsSub($id, $img) {

          //-- Added Image Path For This Page
          $delFilePath="uploads/imagesNews/".$img;

          $k=DB::table('news')->where('id_news', $id)->delete();

          if($k!=="") {

            //-- To Delete Added Image From Folder
            unlink(public_path($delFilePath));
            Session::put('newsAddMsz', 'News Deleted Successfully.');
            return Redirect::to('admin/news');
          }
          else{
            Session::put('newsAddMsz', 'News Not Deleted.');
            return Redirect::to('admin/news');
          }

        }




      //-- Update News (View)
        public function upNews($id) {

          return Redirect::to('admin/news?urlCase=update&urlId='.$id);

        }



      //-- Update News (View)
        public function upNewsSub(Request $request) {



          if(!$request['newsImgUp']==""){

              $pathPass1="";
              $pathPass2="";
              
             
              //-- Image Validations(start)
                  $file2 = Input::file('newsImgUp');
            
                      $Path2="";
                      $idHideNews=$request['upNews_id'];

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

                        Session::put('upNewsImgMsz', $abcde);
                        return Redirect::to('admin/news?urlCase=update&urlId='.$idHideNews);
                           
                      }
                      else{

                      $Path2=1;
                  
                      }
                //-- Image Validations(end)

                //-- Image Upload If Validation Pass(start)
                  if($Path2==1) {

                    $destinationPath2 = 'uploads/imagesNews';
                    $filenameOLD2 = $file2->getClientOriginalName();
                    $randomRegisImage2=rand(10,999999).time().rand(10,999999);
                    $filename2 = $randomRegisImage2.$filenameOLD2;
                    $upload_succes2 = $file2->move($destinationPath2, $filename2);

                    //-- To Delete Added Image From Folder
                      $delFileUp="uploads/imagesNews/".$request['imgUpHide'];
                      unlink(public_path($delFileUp));

                    $pathPass1=1;

                  }
                //-- Image Upload If Validation Pass(End)


            }
            else{
              $pathPass2=1;
              $filename2=$request['imgUpHide'];
            }


          //-- Add Cms Page Code(Start)
            if($pathPass2==1 || $pathPass1=1) {


              $post = $request->all();
              $datas = array(
              'title_news' => $post['upNews_title'],
              'content_news' => $post['upNews_content'],
              'image_news' => $filename2,
              'status_news' => $post['upStatus'],
              );

              $j = DB::table('news')->where('id_news', $post['upNews_id'])->update($datas);

               if($j!=="") {
                Session::put('newsAddMsz', 'News Updated Successfully.');
                return Redirect::to('admin/news');
                }
                else{
                  Session::put('newsAddMsz', 'News Not Updated.');
                  return Redirect::to('admin/news');
                }

            }

        }





      //-- View Selected News (Form <pre> view </pre> button)
        public function showNews($id) {

          return Redirect::to('admin/news?urlCase=view&urlId='.$id);

        }

}
