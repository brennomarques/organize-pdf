<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class ValidateWorkflow extends FormRequest
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
            'auto_initiate' => 'required|boolean',
            'due_date' => 'required|date_format:Y-m-d H:i:s|after:today',
            'message' => '',
            'priority' => 'required|integer',
            'name'=> 'required|min:4|max:255',
            'files' => 'required|array',
            'files.*.file_id' => 'required',
            'files.*.name' => 'required',
            'files.*.workflowSteps' => 'required|array',
            'files.*.workflowSteps.*.user' => 'required|array',
            'files.*.workflowSteps.*.user.name' => 'required|min:4|max:255',
            'files.*.workflowSteps.*.user.email' => 'required|email',
            'files.*.workflowSteps.*.action' => 'required|integer',
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
