<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateBusinessUnit;
use App\Http\Resources\{BusinessUnitResource, BusinessUnitResourceCollection};
use App\Models\{BusinessUnit};
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BusinessUnitController extends Controller
{
    private $model;

    public function __construct(BusinessUnit $model) {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('id')) {
            $fields = $request->get('id');
            return static::show($fields);
        }

        $response = BusinessUnit::paginate(10);
        return new BusinessUnitResourceCollection($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidateBusinessUnit $request)
    {
        try {
            $orderedUuid = (string) Str::orderedUuid();

            $data = $request->all();
            $data['uuid'] = $orderedUuid;

            $response = BusinessUnit::create($data);

            if (!$response) {
                return response(['message' => 'failed to process data'], Response::HTTP_BAD_REQUEST);
            }
            return new BusinessUnitResource($response);

        } catch (\Throwable $th) {
            return response([$th->getTrace()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $search = BusinessUnit::where('uuid', $id)->first();

        if(!$search) {
            return response(['message' => 'No results'], Response::HTTP_OK);
        }

        return new BusinessUnitResource($search);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ValidateBusinessUnit $request, $id)
    {
        $response = BusinessUnit::where('uuid', $id)->first();

        if (!$response) {
            return response(['message' => 'Business unit not found'], Response::HTTP_OK);
        }
        $response->update($request->all());

        return new BusinessUnitResource($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = BusinessUnit::where('uuid', $id)->first();

        if (!$response){
            return response(['message' => 'Business unit not found'], Response::HTTP_OK);
        }

        $response->delete();
        return response(['message' => 'deleted business unit'], Response::HTTP_OK);
    }
}
