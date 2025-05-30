<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Http\Resources\ApiResource;
use App\Http\Resources\ApiOneResource;
use App\Http\Controllers\Controller;


//import Http request
use Illuminate\Http\Request;

//import facade Validator
use Illuminate\Support\Facades\Validator;


class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $data = Period::latest()->get();

         //return collection of posts as a resource
         return new ApiResource(200, 'List Data Semester', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'period' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $data = Period::create([
            'period' => $request->period,
        ]);

         //return response
         return new ApiResource(200, 'Data Semester Berhasil Ditambahkan!', $data->get());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         //find post by ID
         $data = Period::where('id',$id)->first();

         //return single post as a resource
         return new ApiOneResource(200, 'Detail Data Semester!', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'period' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = Period::where('id',$id);

        //create post
        $data->update([
            'period' => $request->period,
        ]);

         //return response
         return new ApiResource(200, 'Data Semester Berhasil Dirubah!', $data->get());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Period::where('id',$id);

        $data->delete();

        return new ApiResource(200, 'Data Semester Berhasil Dihapus!', ['id' => $id]);
    }
}
