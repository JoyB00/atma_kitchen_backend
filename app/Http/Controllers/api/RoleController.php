<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Roles::get();
        return response([
            'message' => 'All Role Retrieved',
            'data' => $role
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'role_name' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $role = Roles::create($data);
        return response([
            'message' => 'Role Created Successfully',
            'data' => $role,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $role = Roles::find($id);
        if (is_null($role)) {
            return response([
                'message' => 'Role Not Found',
                'data' => $role
            ], 404);
        }
        return response([
            'message' => 'A Role Retrieved',
            'data' => $role
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Roles::find($id);

        if (is_null($role)) {
            return response([
                'message' => 'Role Not Found',
                'data' => null
            ], 404);
        }


        $data = $request->all();
        $validate = Validator::make($data, [
            'role_name' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $role->update($data);
        return response([
            'message' => 'Role Updated Successfully',
            'data' => $role
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Roles::find($id);
        $user = User::where('role_id', $id)->get();
        if (is_null($role)) {
            return response([
                'message' => 'Role Not Found',
            ], 404);
        }
        $role->delete();
        if (!is_null($user)) {
            foreach ($user as $key) {
                $key->delete();
            }
        }
        return response([
            'message' => 'Role Deleted Successfully',
            'data' => $role
        ], 200);
    }
}
