<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;

class ISlavel2
{

   public function handle($request, Closure $next)
   {

      if (Session::get('firm_id') == '0'){
          	if (Session::get('role_id') !== '2'){
              	return back()->withInput();
          	}
      	}

      	if (Session::get('firm_id') !== '0'){
        	if (Session::get('role_id') !== '5'){
          		return back()->withInput();
        	}
    	}
      return $next($request);

   }

}