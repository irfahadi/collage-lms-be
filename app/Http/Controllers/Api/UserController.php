<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ApiResource;
use App\Http\Resources\ApiOneResource;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lecture;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    public function index (){
        $users = User::latest()->get();
        $data = $users->map(function ($user) {
            if (in_array($user->role_id, [1, 2, 3, 5])) {
                $lecture = Lecture::with('studyProgram')->where('user_id',$user->id)->first();
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role_id' => $user->role_id,
                    'first_name' => $lecture->first_name ?? null,
                    'last_name' => $lecture->last_name ?? null,
                    'phone_number' => $lecture->phone_number ?? null,
                    'email' => $user->email ?? null,
                    'identity_number' => $lecture->nidn ?? null,
                    'profile_picture' => $lecture->profile_picture ?? null,
                    'role_name' => $user->role->role ?? null,
                    'faculty_name' => $lecture->studyProgram->faculty->name ?? null,
                ];
            } 
            elseif ($user->role_id == 4) {
                $student = Student::with('studyProgram')->where('user_id',$user->id)->first();
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role_id' => $user->role_id,
                    'first_name' => $student->first_name ?? null,
                    'last_name' => $student->last_name ?? null,
                    'phone_number' => $student->phone_number ?? null,
                    'email' => $user->email ?? null,
                    'identity_number' => $student->nim ?? null,
                    'profile_picture' => $student->profile_picture ?? null,
                    'role_name' => $user->role->role ?? null,
                    'faculty_name' => $student->studyProgram->faculty->name ?? null,
                ];
            } 
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'          => ['required', 'string', 'min:6'],
            'role_id'           => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
            'first_name'        => ['required', 'string', 'max:255'],
            'last_name'         => ['required', 'string', 'max:255'],
            'identity_number'   => ['required', 'string', 'max:100'],
            'study_program_id'  => ['required', 'integer', 'exists:study_program,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create User
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id,
        ]);

        // Generate API token
        $token = Password::broker('users')->createToken($user);

        if (in_array($request->role_id, [1, 2, 3])) {
            Lecture::create([
                'user_id'         => $user->id,
                'first_name'      => $request->first_name,
                'last_name'       => $request->last_name,
                'nidn'            => $request->identity_number,
                'study_program_id'=> $request->study_program_id,
                'profile_picture' => $request->profile_picture,
                'birthdate'       => $request->birthdate,
                'phone_number'    => $request->phone_number,
            ]);
        } elseif ($request->role_id === 4) {
            Student::create([
                'user_id'         => $user->id,
                'first_name'      => $request->first_name,
                'last_name'       => $request->last_name,
                'nim'             => $request->identity_number,
                'study_program_id'=> $request->study_program_id,
                'profile_picture' => $request->profile_picture,
                'birthdate'       => $request->birthdate,
                'phone_number'    => $request->phone_number,
            ]);
        }

        //Send welcome email with token
        Mail::raw("Selamat datang, {$user->name}!\nBerikut link untuk reset password Anda: http://skuring.com/resetpassword/{$token}?email={$request->email}", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Welcome to MyApp');
        });

        return response()->json([
            'message' => 'User berhasil dibuat',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }
    /**
     * Update an existing user and its related Lecture or Student record.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255'],
            'password'          => ['nullable', 'string', 'min:6'],
            'role_id'           => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
            'first_name'        => ['required', 'string', 'max:255'],
            'last_name'         => ['required', 'string', 'max:255'],
            'identity_number'   => ['required', 'string', 'max:100'],
            'study_program_id'  => ['required', 'integer', 'exists:study_program,id'],
            'profile_picture'   => ['nullable', 'string'],
            'birthdate'         => ['nullable', 'date'],
            'phone_number'      => ['nullable', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if (in_array($request->role_id, [1, 2, 3, 5])) {
            Lecture::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name'      => $request->first_name,
                    'last_name'       => $request->last_name,
                    'nidn'            => $request->identity_number,
                    'study_program_id'=> $request->study_program_id,
                    'profile_picture' => $request->profile_picture,
                    'birthdate'       => $request->birthdate,
                    'phone_number'    => $request->phone_number,
                ]
            );
        } elseif ($request->role_id === 4) {
            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name'      => $request->first_name,
                    'last_name'       => $request->last_name,
                    'nim'             => $request->identity_number,
                    'study_program_id'=> $request->study_program_id,
                    'profile_picture' => $request->profile_picture,
                    'birthdate'       => $request->birthdate,
                    'phone_number'    => $request->phone_number,
                ]
            );
        }

        return response()->json([
            'message' => 'User berhasil diperbarui',
            'user'    => $user,
        ], 200);
    }
    /**
     * Show a user's data joined with lecture or student based on role_id.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $userData = $user->toArray();

        if (in_array($user->role_id, [1, 2, 3, 5])) {
            $lecture = Lecture::where('user_id', $user->id)->first();
            if ($lecture) {
                $userData = array_merge($userData, $lecture->toArray(), [
                    'identity_number' => $lecture->nidn ?? null,
                ]);
            }
        } elseif ($user->role_id === 4) {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                $userData = array_merge($userData, $student->toArray(), [
                    'identity_number' => $student->nim ?? null,
                ]);
            }
        }

        return response()->json([
            'message' => 'Data user berhasil diambil',
            'data'    => $userData,
        ], 200);
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // dd($user);

        // Hapus relasi sesuai role
        if (in_array($user->role_id, [1, 2, 3, 5])) {
            Lecture::where('user_id', $user->id)->delete();
        } elseif ($user->role_id === 4) {
            Student::where('user_id', $user->id)->delete();
        }

        // Hapus user
        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus',
        ], 200);
    }
}

