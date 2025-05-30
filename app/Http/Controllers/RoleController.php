<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Resources\ApiResource;
use App\Http\Resources\ApiOneResource;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $data = Role::latest()->get();

         //return collection of posts as a resource
         return new ApiResource(200, 'List Data Role', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            // 'code' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $data = Role::create([
            'role' => $request->role,
        ]);

         //return response
         return new ApiResource(200, 'Data Role Berhasil Ditambahkan!', $data->get());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         //find post by ID
         $data = Role::where('id',$id)->first();

         //return single post as a resource
         return new ApiOneResource(200, 'Detail Data Role!', $data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            // 'code' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = Role::where('id',$id);

        //create post
        $data->update([
            'role' => $request->role,
        ]);

         //return response
         return new ApiResource(200, 'Data Role Berhasil Dirubah!', $data->get());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Role::where('id',$id);

        $data->delete();

        return new ApiResource(200, 'Data Role Berhasil Dihapus!', ['id' => $id]);
    }
}
