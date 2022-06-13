<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            return BusinessUnitController::show($fields);
        }

        $response = BusinessUnit::paginate(10);
        return new BusinessUnitResourceCollection($response);
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
    public function store(Request $payload)
    {
        $orderedUuid = (string) Str::orderedUuid();

        $data = $payload->all();
        $data['uuid'] = $orderedUuid;

        $response = BusinessUnit::create($data);

        if (!$response) {
            return response(['message' => 'failed to process data'], Response::HTTP_BAD_REQUEST);
        }
        return new BusinessUnitResource($response);

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

    /**
     * Validate inputted data for created and updated resource
     *
     * @return array
     */
    protected function validatedInput(): array
    {
        return $this->request->validate(
            [
                'name'  => ['nullable', 'string', 'max:255'],
                'document' => ['nullable', 'string'],
                // 'email' => ['required', 'email'],
            ]
        );
    }
}
