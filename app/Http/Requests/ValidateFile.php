<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class ValidateFile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $mimes = implode(',', File::SUPPORTED_FORMATS);

        return [
            'file' => [
                'bail',
                'required',
                'file',
                "mimes:{$mimes}",
                'max:25600' //25mb
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors' => $validator->errors(),
            'status' => true
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
