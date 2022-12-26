<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\role_permission_rel;

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
        //$role_permission = $this->role_permission($config['user']->role_permission);
       // $config['permission'] = $role_permission['permission'];
        config($config);
        return $next($request);
    }

    public function role_permission($role_id)
    {
        $role = new Role;
        //$permission_type_rel = new permission_type_rel;
        $role_permission_rel = new role_permission_rel;
        $role_data = $role->get_role($role_id);
        if (!$role_data) 
        {
            return redirect("logout");
        }
        $module = [];
        $permission = [];
    }

}
