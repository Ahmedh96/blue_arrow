<?php

namespace App\Http\Requests\BackEnd\Notes;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
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
            'type'      => ['required', 'string'],
            'content'   => ['required', 'string'],
            'image'     => ['nullable', 'image', 'mimes:jpg,png,jpeg' , 'max:2048'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'type.required'   => 'A Type is required',
            'content.required'      => 'A Content is required',
        ];
    }
}
