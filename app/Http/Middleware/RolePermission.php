<?php

namespace App\Http\Middleware;

use App\Models\EmployeeModel;
use App\Models\org_type_rel;
use App\Models\org_user_rel;
use App\Models\permission_type_rel;
use App\Models\role;
use App\Models\role_permission_rel;
use App\Models\organization;
use App\Models\agent_org_rel;

use Closure;
use Illuminate\Http\Request;

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
      //  return response()->view('layouts/404', [], 404);

        $EmployeeModel = new EmployeeModel;
        $org_user_rel = new org_user_rel;
        $org_type_rel = new org_type_rel;
        $organization = new organization;
        $agent_org_rel = new agent_org_rel;

        $config['org_pending_count'] = 0;

        if (!session('user.id')) {return redirect("logout");}
        $user_id = session('user.id');

        $user_data = $EmployeeModel->get_user($user_id);
        if (!$user_data) {return redirect("logout");}

        $get_organization = $org_user_rel->get_organization($user_id);
        if (!$get_organization) {return redirect("logout");}

        if ($get_organization->status != 1 || $user_data->status != 1 || !$user_data->role_permission) {
            return redirect("logout");
        }

        $type_id = $org_type_rel->get_org_type_rel($get_organization->org_id);
        if (!$type_id) {
            return redirect("logout");
        }
      
        if($type_id->type_id == 1){
            $config['org_pending_count'] = $organization->get_org_pending_count([['id','=',null]]);
        }

        $role_permission = $this->role_permission($user_data->role_permission, $type_id->type_id);
        $config['user'] = $user_data->toArray();
        $config['organization'] = $get_organization->toArray();
        $config['organization']['type'] =  $type_id->type_id;
        $config['permission'] = $role_permission['permission'];
        $config['module'] = $role_permission['module'];
        if($type_id->type_id == 5){
           $agent_org_rel =  $agent_org_rel->get_subscriber_org_rel(['org_id'=>$config['organization']['org_id']]);
           $config['subscriber']['subscriber_id'] = $agent_org_rel['subscriber_id'];
        }
        config($config);

        return $next($request);
    }
    public function role_permission($role_id, $type_id)
    {

        $role = new role;
        $permission_type_rel = new permission_type_rel;
        $role_permission_rel = new role_permission_rel;

        $role_data = $role->get_role($role_id);
        if (!$role_data) {return redirect("logout");}
        $module = [];
        $permission = [];
       
        if ($role_data->created_org == 0) {
            $get_permission_type_rel = $permission_type_rel->get_permission_type_rel($type_id);
            if (!$get_permission_type_rel) {return redirect("logout");}
            foreach ($get_permission_type_rel as $get_permission_type_rel) {
                $permission[] = $get_permission_type_rel->per_name;
                $module[$get_permission_type_rel->per_module] = $get_permission_type_rel->per_module;
            }
            return ['module' => $module, 'permission' => $permission];
        }


            $get_permission_type_rel = $permission_type_rel->get_permission_type_rel($type_id);
            if (!$get_permission_type_rel) {return redirect("logout");}

            foreach ($get_permission_type_rel as $get_permission_type_rel) {
                if($role_permission_rel->select_permission(['role_id'=>$role_data->role_id,'permission_id'=>$get_permission_type_rel->permission_id])){
                $permission[] = $get_permission_type_rel->per_name;
                $module[$get_permission_type_rel->per_module] = $get_permission_type_rel->per_module;
                }

            }
            return ['module' => $module, 'permission' => $permission];
    }
}
