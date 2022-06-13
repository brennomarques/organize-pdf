<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Models\{File};
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    private $model;

    public function __construct(File $model)
    {
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
        if (($request->has('id') && !empty($request->get('id')))) {
            $id = $request->get('id');
            $search = File::where('uuid', $id)->first();

            if (!$search) {
                return response(['message' => 'File not found.'], Response::HTTP_OK);
            }

            $headers = ['Content-Type' => 'application/pdf'];
            return response(Storage::disk('public')->get($search->path), Response::HTTP_OK, $headers);
        }
        return response(['message' => 'Invalid download token id.'], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!($request->hasFile('file') && $request->file('file')->isValid())) {
            return response(['message' => 'Invalid file content or empty.'], Response::HTTP_BAD_REQUEST);
        }

        if (!($request->file('file')->getClientMimeType() === 'application/pdf' || $request->file('file')->getClientOriginalExtension() === 'pdf')) {
            return response(['message' => 'Invalid file content.'], Response::HTTP_BAD_REQUEST);
        }

        $orderedUuid = (string) Str::orderedUuid();
        $payload = [
            'uuid' => $orderedUuid,
            'name' => $request->file('file')->getClientOriginalName(),
            'description' => $request->description,
            'path' => $request->file('file')->store('folders/files/default', 'public')
        ];
        $result = File::create($payload);

        return new FileResource($result);

    }
}
