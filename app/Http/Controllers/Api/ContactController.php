<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateContact;
use App\Http\Resources\ContactResource;
use App\Http\Resources\ContactResourceCollection;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('contact')) {
            $fields = $request->get('contact');
            return static::show($fields);
        }

        $response = Contact::paginate(10);
        return new ContactResourceCollection($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ValidateContact  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidateContact $request)
    {
        try {
            $orderedUuid = (string) Str::orderedUuid();

            $data = $request->all();
            $data['uuid'] = $orderedUuid;
            $data['user_id'] = auth()->user()->id;

            $response = Contact::create($data);

            if (!$response) {
                return response(['message' => 'failed to process data'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return new ContactResource($response);

        } catch (\Throwable $th) {
            return response([$th->getTrace()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $search = Contact::where('uuid', $id)->first();

        if(!$search) {
            return response(['message' => 'No contact result'], Response::HTTP_OK);
        }

        return new ContactResource($search);
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
            $response = Contact::where('uuid', $id)->first();

            if (!$response) {
                return response(['message' => 'Contact not found'], Response::HTTP_OK);
            }
            $response->update($request->all());

            return new ContactResource($response);
        } catch (\Throwable $th) {
            return response([$th->getTrace()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
