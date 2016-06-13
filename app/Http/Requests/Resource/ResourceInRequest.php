<?php

namespace App\Http\Requests\Resource;

use App\Http\Requests\Request;

class ResourceInRequest extends Request
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
            'resource' => 'required|numeric',
            'amount' => 'required|numeric|min:1',
            'content' => 'min:2|max:200',
        ];
    }
}
