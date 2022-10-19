<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateLogin;
use App\Http\Requests\ValidateUser;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * store a newly created user register in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(ValidateUser $request)
    {
        try {
            $orderedUuid = (string) Str::orderedUuid();

            $data = $request->all();
            $data['uuid'] = $orderedUuid;
            $data['password'] =  Hash::make($data['password']);
            $data['status'] =  User::CONFIRM_REGISTRATION;

            $response = User::create($data);

            if (!$response) {
                return response(['message' => 'Register creation failed.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            Mail::to($response->email)->send(new RegisterMail($response));

            return new UserResource($response);

        } catch (\Throwable $th) {
            return response([$th->getTrace()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  \Illuminate\Http\ValidateLogin  $request
     * @return \Illuminate\Http\Response
     */
    public function login(ValidateLogin $request)
    {
        $data = $request->all();
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password']
        ];
        $user = User::where('email',  $credentials['email'])->first();

        if (!$user) {
            return response(['message' => 'User not found'], Response::HTTP_UNAUTHORIZED);
        }

        if ($user['status'] === User::CONFIRM_REGISTRATION) {
            return response(['message' => 'Unconfirmed user'], Response::HTTP_UNAUTHORIZED);
        }

        if ($user['status'] === User::DISABLED) {
            return response(['message' => 'Account disabled, contact support'], Response::HTTP_UNAUTHORIZED);
        }

        if (! $token = auth('api')->attempt($credentials)) {
            return response(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return new AuthResource($token);
    }

    /**
     * Verify Account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyAccount(Request $request)
    {
        dd($request);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();
        return response(['message' => 'Successfully logged out'], Response::HTTP_OK);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return new AuthResource(auth()->refresh());
    }

}
