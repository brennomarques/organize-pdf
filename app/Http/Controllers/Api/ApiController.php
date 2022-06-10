<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller {
    /**
     * URL describing the problem type; defaults to HTTP status codes.
     *
     * @var string
     */
    protected $type = 'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html';

    public function sendError(string $message, $data = [], $code = 500)
    {
        return response()->json([
            'type' => $this->type,
            'result' => $message,
            'data' => $data
        ], $code);
    }

}
