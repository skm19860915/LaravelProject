<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;

class ISlavel3
{

   public function handle($request, Closure $next)
   {

      if (Session::get('firm_id') == '0'){
          	if (Session::get('role_id') !== '3'){
              	return back()->withInput();
          	}
      	}

      	if (Session::get('firm_id') !== '0'){
        	if (Session::get('role_id') !== '6'){
          		return back()->withInput();
        	}
    	}
      return $next($request);

   }

}