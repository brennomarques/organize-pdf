<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FileController extends ApiController
{
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
        // $input = $request->validate([
        //     'file' => 'file',
        //     'description' => 'string|max:10'
        // ]);


        try {
            // throw new Exception("aqui tem");
            if (!($request->hasFile('file') && $request->file('file')->isValid())) {
                return $this->sendError('Invalid file content or empty.', [], 400);
            }

            if (!($request->file('file')->getClientMimeType() === 'application/pdf' || $request->file('file')->getClientOriginalExtension() === 'pdf')) {
                return $this->sendError('Invalid file content.', [], 400);
            }

            // echo "MimeType: " . $request->file('file')->getClientMimeType()."<br>";
            // echo "OriginalExtension: " . $request->file('file')->getClientOriginalExtension()."<br>";
            // echo "OriginalName: " . $request->file('file')->getClientOriginalName()."<br>";
            // echo "Size: " . $request->file('file')->getSize()."<br>";
            // echo "<br>";
            // $uuid = Str::uuid()->toString();

            $uuid = (string) Str::uuid();

            $orderedUuid = (string) Str::orderedUuid();
            $isUuid = Str::isUuid($orderedUuid);

            $path = $request->file('file')->store('files', 'public');

            return $this->sendError('created file successfully', [$uuid, $isUuid, $orderedUuid, $path], 201);

        } catch (\Exception $ex) {
            return $this->sendError('Invalid file content or empty.', $ex->getTrace(), 400);
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
        return response()->json([
            'name' => 'Ronaldo santos',
            'status' => true,
            'photo' => false,
            'extraData' => [],
            'id:' => $id
        ]);
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
