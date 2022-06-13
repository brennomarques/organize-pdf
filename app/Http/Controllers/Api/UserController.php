<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            return UserController::show($fields);
        }
        return response(['message' => 'Invalid user id.'], Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $orderedUuid = (string) Str::orderedUuid();

        $data = $request->all();
        $data['uuid'] = $orderedUuid;
        $data['password'] =  Hash::make($data['password']);

        $response = User::create($data);

        if (!$response) {
            return response(['message' => 'failed to process data'], Response::HTTP_BAD_REQUEST);
        }

        return new UserResource($response);
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
            return response(['message' => 'No results'], Response::HTTP_OK);
        }

        return new UserResource($search);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $user = $request->all();
        $response = User::where('uuid', $id)->first();

        if (!$response) {
            return response(['message' => 'User not found.'], Response::HTTP_OK);
        }

        if (isset($user['password'])) {
            $user['password'] =  Hash::make($user['password']);
        }

        $response->update($user);

        return new UserResource($response);
    }
}
