<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;

class ISactive
{

   public function handle($request, Closure $next)
   {

      if (Auth::User()->status == '0'){
      	Auth::logout();
      	return redirect(route('login'))->withInfo('Your account is Susspanded !');
      }
      return $next($request);

   }

}