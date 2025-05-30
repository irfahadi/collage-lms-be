<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request) {
        $response = Http::asForm()->post(config('services.oauth.url') . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => config('services.oauth.id'),
            'client_secret' => config('services.oauth.secret'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '',
        ]);

        return $response->json();
    }

    public function __invoke2(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {
            $user = auth()->user();

        //return response
         return new UserResource(true, 'Anda Berhasil Login!', $user)->additional([
                'token' => $user->createToken('myAppToken')->plainTextToken,
            ]);
        }

        return response()->json([
            'message' => 'Your credential does not match.',
        ], 401);
    }
}
