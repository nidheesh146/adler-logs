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
        //print_r($config['user']['role_permission']); exit;
        $role_array = explode(',', $config['user']['role_permission']);
        //print_r($role_array); exit;
        $role_array_len = count($role_array);
            $role_permission = $this->role_permission($config['user']['role_permission']);
           // $role_permission = $this->role_permission($role_array[$i]);
        
        //print_r($role_permission); exit;
        $config['permission'] = $role_permission['permission'];
        config($config);
        return $next($request);
    }

    public function role_permission($roles)
    {
        $role = new Role;
        $role_permission_rel = new role_permission_rel;
        $role_array = explode(',', $roles);
        //print_r($role_array); exit;
        $role_array_len = count($role_array);
        $module = [];
        $permission = [];
        for($i=0;$i<$role_array_len;$i++)
        {
            $role_data = $role->get_role($role_array[$i]);
            if (!$role_data) 
            {
                return redirect("logout");
            }
           
            $get_permission = $role_permission_rel->get_permissions(['role_id'=>$role_array[$i]]);
            if(!$get_permission)
            {
                return redirect("logout");
            }
            foreach($get_permission as $get_permission)
            {
                $permission[] = $get_permission->per_name;
                $module[$get_permission->per_module]= $get_permission->per_module;
            }
        }
        return ['module' => $module, 'permission' => $permission];
    }

}
