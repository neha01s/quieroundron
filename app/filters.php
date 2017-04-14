<?php
Route::filter('role', function()
{ 
  if ( Auth::user()->role !==2) {
     // do something
     return Redirect::to('/admin'); 
   }
}); 
?>