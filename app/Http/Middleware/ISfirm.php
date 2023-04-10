<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class ISfirm
{

   public function handle($request, Closure $next)
   {
        if (Session::get('firm_id') == '0'){
          if (Session::get('role_id') == '1'){
              return redirect(route('admin.dashboard'));
          }
          if (Session::get('role_id') == '2'){
              return redirect(route('admin.userdashboard'));
          }
          if (Session::get('role_id') == '3'){
              return redirect(route('admin.supportdashboard'));
          }
        }
       return $next($request);
   }

}