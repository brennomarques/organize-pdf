<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{File};
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    private $model;

    private $fileSize = 2048; // 2MB

    public function __construct(File $model) {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        try {
            // throw new Exception("aqui tem");
            // dd($request->file('file')->isError());
            // if ($request->file('file')->getSize() > $this->fileSize) {
            //     return $this->sendError('The uploaded file exceeds the max file size', [], 400);
            // }

            if (!($request->hasFile('file') && $request->file('file')->isValid())) {
                return response(['message' => 'Invalid file content or empty.'], Response::HTTP_BAD_REQUEST);
            }

            if (!($request->file('file')->getClientMimeType() === 'application/pdf' || $request->file('file')->getClientOriginalExtension() === 'pdf')) {
                return response(['message' => 'Invalid file content.'], Response::HTTP_BAD_REQUEST);
            }

            // echo "MimeType: " . $request->file('file')->getClientMimeType()."<br>";
            // echo "OriginalExtension: " . $request->file('file')->getClientOriginalExtension()."<br>";
            // echo "OriginalName: " . $request->file('file')->getClientOriginalName()."<br>";
            // echo "Size: " . $request->file('file')->getSize()."<br>";
            // echo "<br>";

            // $uuid = (string) Str::uuid();

            $orderedUuid = (string) Str::orderedUuid();
            // $isUuid = Str::isUuid($orderedUuid);
            // dd($request->description);
            // $path = $request->file('file')->store('files/default', 'public');

            $payload = [
                'uuid' => $orderedUuid,
                'name' => $request->file('file')->getClientOriginalName(),
                'description' => $request->description,
                'path' => $request->file('file')->store('folders/files/default', 'public')
            ];
            $result = File::create($payload);

            $response = [
                'id' => $result->uuid,
                'name' => $result->name,
                'description'=> $result->description
            ];

            return response($response, Response::HTTP_CREATED);

        } catch (\Exception $ex) {
            return response(['message' => 'Invalid file content or empty.', $ex->getTrace()], Response::HTTP_BAD_REQUEST);
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
        $search = File::where('uuid', $id)->first();

        if(!$search) {
            return response(['message' => 'No results'], Response::HTTP_OK);
        }

        $headers = ['Content-Type' => 'application/pdf'];

        return response(Storage::disk('public')->get($search->path), Response::HTTP_OK, $headers);

        // return Storage::download($search->path, $search->name, $headers);
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
        //
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
