<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateUser;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('email')) {
            $fields = $request->get('email');
            return static::show($fields);
        }
        return response(['message' => 'Invalid user id.'], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $email
     * @return \Illuminate\Http\Response
     */
    public function show($email)
    {
        $search = User::where('email', $email)->first();

        if(!$search) {
            return response(['message' => 'No user result'], Response::HTTP_OK);
        }

        return new UserResource($search);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $user = $request->all();
            $response = User::where('uuid', $id)->first();

            if (!$response) {
                return response(['message' => 'User not found.'], Response::HTTP_OK);
            }

            if (isset($user['password'])) {
                $user['password'] =  Hash::make($user['password']);
            }

            if (isset($user['email'])) {
                unset($user['email']);
            }

            $response->update($user);
            return new UserResource($response);

        } catch (\Throwable $th) {
            return response([$th->getTrace()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
