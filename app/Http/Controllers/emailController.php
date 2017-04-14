<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;


class emailController extends Controller {

   

      public function emailFback(Request $request) {

        $post = $request->all();
        
        $data['nameFB']=$post['name_FB'];
        $data['emailFB']=$post['email_FB'];
        $data['mszFB']=$post['msz_FB'];
         
         Mail::send('emailView.fBack', ['data' => $data], function($message) use ($data)
            {
                $message->from('amit.kumar@ldh.01s.in', "Quiero Un Drone");
                $message->subject("Feedback/Contact Message From Quiero Un Drone User");
                $message->to('g.vaid@ldh.01s.in');
            });

          if($request->ajax()){

            if( count(Mail::failures()) > 0 ) {

                $fback[] = array(
                  'FResult'  => '<p style="color: #F65B47;">Por favor, inténtelo de nuevo.</p>'
                );
                return response()->json([
                        'fback' => $fback
                      ]);
                
            } else {

                $fback[] = array(
                'FResult'  => '<p style="color: #F65B47;">racias por escribirnos, estaremos pronto en contacto contigo.</p>',
                );
                return response()->json([
                        'fback' => $fback
                        ]);
                    
            }

          }

        }  


}

