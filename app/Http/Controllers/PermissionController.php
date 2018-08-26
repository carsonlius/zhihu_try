<?php

namespace App\Http\Controllers;

use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Compound;
use Ultraware\Roles\Models\Permission;

class PermissionController extends Controller
{
    protected $repository_permission;

    /**
     * PermissionController constructor.
     * @param $repository_permission
     */
    public function __construct(PermissionRepository $repository_permission)
    {
        $this->repository_permission = $repository_permission;
    }



    /**
     * 递归权限
     */
    public function recursiveList()
    {
        try {
            $list_permission = $this->repository_permission->recursiveList();
            $status = 0;

            return response()->json(compact('list_permission', 'status'));
        } catch (\Exception $e){
            $status = 1478;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }


    public function list()
    {
        try {
            $list_permissions = $this->repository_permission->list();
            $status =0;
            return response()->json(compact('list_permissions', 'status'));
        } catch (\Exception $e) {
            $status = 1478;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->repository_permission->store();
            $status = 0;
            return response()->json(compact('status'));
        } catch (\Exception $e) {
            $status = 4178;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        $permission = json_encode($permission, JSON_UNESCAPED_UNICODE);
        return view('permissions.edit')->with(compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        try {
            $this->repository_permission->update();
            $status = 0;
            return response()->json(compact('status'));
        } catch (\Exception $e) {
            $status = 4178;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        try {
            $res_del = $permission->delete();
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
}
