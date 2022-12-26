<?php

namespace App\Http\Controllers\web\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use App\Models\role_permission_rel;
use Validator;
class RolePermissionController extends Controller
{
    public function __construct()
    {
        $this->Permission = new Permission;
        $this->Role = new Role;
        $this->role_permission_rel = new role_permission_rel;
    }
    // public function moduleList()
    // {
    //     $modules = $this->Permission->get_modules();
    //     return view('pages\employee\module-list');
    // }
    // public function moduleAdd()
    // {
    //     return view('pages\employee\module-add');
    // }

    public function roleList(Request $request, $role_id = null)
    {
        $validator = Validator::make($request->all(), [
            'role' => ['required', 'min:1', 'max:20'],
            'description' => ['required', 'min:1', 'max:115'],
        ]);
        if (!$validator->errors()->all()) {
            $datas['role_name'] = $request->role;
            $datas['role_description'] = $request->description;
            if (!$request->role_id) {
                $this->Role->insert_role($datas);
                $request->session()->flash('success', 'Role  has been successfully inserted');
                return redirect("settings/role");
            }
            $this->Role->update_role($datas, $request->role_id);
            $request->session()->flash('success', 'Role  has been successfully updated');
            return redirect("settings/role");
        }
        $data['role'] = $this->Role->get_roles();
        if ($request->role_id) {
            $edit = $this->Role->get_role($request->role_id);
            //print_r($edit);exit;
            return view('pages\settings\role',compact('data','edit'));
        }
        else
        return view('pages\settings\role',compact('data'));
    }
    public function deleteRole(Request $request,)
    {
        //$this->role_permission_rel->delete_permission($this->hashDecode($role));
        $this->Role->delete_role($request->role_id);
        $request->session()->flash('succs', 'Role has been successfully deleted');
        return redirect("settings/role" );
    }
    public function moduleList()
    {
        $data['module'] = $this->Permission->get_modules();
        return view('pages\settings\module',compact('data'));
    }
    public function permissionList()
    {
        $get_permission = $this->Permission->get_permission();
        foreach ($get_permission as $get_permission) {
            $data['permission'][$get_permission['per_module']][$get_permission['permission_id']] = $get_permission['per_display_name'];
        }
        return view('pages\settings\permission',compact('data'));
    }
    public function rolePermission(Request $request, $role_id)
    {
        if ($request->isMethod('post')) {
            $this->role_permission_rel->delete_permission($role_id);
            if ($request->permission) {
                $permarray = [];
                foreach ($request->permission as $perm) {
                    $permarray[] = [
                        'role_id' => $role_id,
                        'permission_id' => $perm,
                    ];
                }
                $roleper = $this->Role->get_role($role_id);
                // if (count($permarray) > 0 && $roleper->created_org != 0) {
                $this->role_permission_rel->insert_permission($permarray);
                // }
            }
            $request->session()->flash('success', 'Permission has been successfully updated');
            return redirect("settings/role-permission/".$role_id);
        }
        // $data['permission'] = [];
        // $get_permission = $this->Permission->get_permission();
        // foreach ($get_permission as $get_permission) {
        //     $data['permission'][$get_permission['per_module']][$get_permission['permission_id']]['name'] = $get_permission['per_display_name'];
        //     $get_perm = $this->role_permission_rel->select_permission(['permission_id' => $get_permission['permission_id'], 'role_id' => $role_id]);
        //     $data['permission'][$get_permission['per_module']][$get_permission['permission_id']]['checked'] = $get_perm ? 'checked' : '';
        // }

        $get_permission = $this->Permission->get_permission();
        foreach ($get_permission as $get_permission) {
            $data['permission'][$get_permission['per_module']][$get_permission['permission_id']] = $get_permission['per_display_name'];
            $get_perm = $this->role_permission_rel->select_permission(['permission_id' => $get_permission['permission_id'], 'role_id' => $role_id]);
            //$data['permission'][$get_permission['per_module']][$get_permission['permission_id']]['checked'] = $get_perm ? 'checked' : '';
        }
        return view('pages\settings\role-permission',compact('data'));
    }
}
