<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class RolePermission
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $User = new User;
        if (!session('user.id')) {return redirect("logout");}
        $user_id = session('user.id');
        $config['user'] = $User->get_user_details(['user_id'=>$user_id])->toArray();
        if(empty($config['user']) || $config['user']['status'] != 1){
            return redirect("logout");
        }
        config($config);
        return $next($request);
    }
}
