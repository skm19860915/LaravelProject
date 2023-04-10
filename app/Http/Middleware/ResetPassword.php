<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;

class ResetPassword
{

    public function handle($request, Closure $next)
    {
        if (Session::get('is_reset_pass') == '0'){

          if (Session::get('role_id') == '4'){
            return redirect(route('firm.first_reset_admin_password'))->with('info','First Reset Password');
          }
          if (Session::get('role_id') == '5'){
            return redirect(route('firm.first_reset_user_password'))->with('info','First Reset Password');
          }
          if (Session::get('role_id') == '6'){
            return redirect(route('firm.first_reset_client_password'))->with('info','First Reset Password');
          }
        }
       return $next($request);
    }

}