<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Http\Resources\ApiResource;
use App\Http\Resources\ApiOneResource;
use App\Http\Controllers\Controller;


//import Http request
use Illuminate\Http\Request;

//import facade Validator
use Illuminate\Support\Facades\Validator;


class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $data = Faculty::latest()->get();

         //return collection of posts as a resource
         return new ApiResource(200, 'List Data Fakultas', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $data = Faculty::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'established_year' => $request->established_year,
            'dean_name' => $request->dean_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
        ]);

         //return response
         return new ApiResource(200, 'Data Fakultas Berhasil Ditambahkan!', $data->get());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         //find post by ID
         $data = Faculty::where('id',$id)->first();

         //return single post as a resource
         return new ApiOneResource(200, 'Detail Data Fakultas!', $data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = Faculty::where('id',$id);

        //create post
        $data->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'established_year' => $request->established_year,
            'dean_name' => $request->dean_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
        ]);

         //return response
         return new ApiResource(200, 'Data Fakultas Berhasil Dirubah!', $data->get());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Faculty::where('id',$id);

        $data->delete();

        return new ApiResource(200, 'Data Fakultas Berhasil Dihapus!', ['id' => $id]);
    }
}
