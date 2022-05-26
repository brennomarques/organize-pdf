<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

        return [
            'file' => 'required',
            // 'files.*' => 'required|mimes:pdf',
            // 'file' => 'required|mimes:pdf|max:20000'
            // 'file' => 'required|file|mimes:pdf|max:100'
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Invalid file content, send only a file at a time',
        ];
    }
}
