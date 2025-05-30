<?php

namespace App\Http\Controllers;

use App\Models\StudyProgram;
use App\Http\Resources\ApiResource;
use App\Http\Resources\ApiOneResource;
use App\Http\Controllers\Controller;


//import Http request
use Illuminate\Http\Request;

//import facade Validator
use Illuminate\Support\Facades\Validator;


class StudyProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter faculty_id dari query string
        $facultyId = $request->query('faculty_id');

        // Query dasar
        $query = StudyProgram::with('faculty')->latest();

        // Jika faculty_id ada, filter query-nya
        if ($facultyId) {
            $query->where('faculty_id', $facultyId);
        }

        // Ambil data
        $data = $query->get();

         //return collection of posts as a resource
         return new ApiResource(200, 'List Data Program Studi', $data);
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
        $data = StudyProgram::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'established_year' => $request->established_year,
            'head_of_program' => $request->head_of_program,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'faculty_id' => $request->faculty_id,
        ]);

         //return response
         return new ApiResource(200, 'Data Program Studi Berhasil Ditambahkan!', $data->get());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         //find post by ID
         $data = StudyProgram::where('id',$id)->with('faculty')->first();

         //return single post as a resource
         return new ApiOneResource(200, 'Detail Data Program Studi!', $data);
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

        $data = StudyProgram::where('id',$id);

        //create post
        $data->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'established_year' => $request->established_year,
            'head_of_program' => $request->head_of_program,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'faculty_id' => $request->faculty_id,
        ]);

         //return response
         return new ApiResource(200, 'Data Program Studi Berhasil Dirubah!', $data->get());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = StudyProgram::where('id',$id);

        $data->delete();

        return new ApiResource(200, 'Data Program Studi Berhasil Dihapus!', ['id' => $id]);
    }
}
