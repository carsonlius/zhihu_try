<?php

namespace App\Http\Controllers;

use App\Repositories\RoleRepository;
use Ultraware\Roles\Models\Role;

class RoleController extends Controller
{
    protected $repository_role;

    /**
     * RoleController constructor.
     * @param $repository_role
     */
    public function __construct(RoleRepository $repository_role)
    {
        $this->repository_role = $repository_role;
    }

    /**
     * API编辑角色
     */
    public function update()
    {
        try {
            $this->repository_role->update();
            $status = 0;
            return response()->json(compact('status'));
        } catch (\Exception $e) {
            $status = 4178;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }

    /**
     * 编辑角色
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Role $role)
    {
        $role = json_encode($role->toArray(), JSON_UNESCAPED_UNICODE);
        return view('roles.edit')->with(compact('role'));
    }

    public function delete(Role $role)
    {
        try {
            $res_del = $role->delete();
            if (!$res_del) {
                throw new \Exception('删除失败');
            }
            $status = 0;
            $msg = '删除成功';
            return response()->json(compact('status', 'msg'));
        } catch (\Exception $e) {
            $status = 4178;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }

    /**
     * API角色列表
     *
     */
    public function list()
    {
        try {
            $list_roles = $this->repository_role->list();
            $status =0;
            return response()->json(compact('list_roles', 'status'));
        } catch (\Exception $e) {
            $status = 1478;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }

    /**
     * 角色列表
     */
    public function index()
    {
        return view('roles.index');
    }

    /**
     * 创建新的角色
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * 存储角色
     */
    public function store()
    {
        try {
            $this->repository_role->store();
            $status = 0;
            return response()->json(compact('status'));
        } catch (\Exception $e) {
            $status = 4178;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }
}
