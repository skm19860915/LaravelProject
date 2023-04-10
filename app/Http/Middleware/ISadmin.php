<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class ISadmin
{

   public function handle($request, Closure $next)
   {

      if (Session::get('firm_id') !== '0'){

        if (Session::get('role_id') == '4'){
          return redirect(route('firm.admindashboard'));
        }
        if (Session::get('role_id') == '5'){
          return redirect(route('firm.admindashboard'));
        }
        if (Session::get('role_id') == '6'){
          return redirect(route('firm.clientdashboard'));
        }
      }/*else{
        
      }*/
      return $next($request);

   }

}