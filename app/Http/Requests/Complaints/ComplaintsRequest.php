<?php

namespace App\Http\Requests\Complaints;

use App\Http\Requests\Request;

class ComplaintsRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return false;
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
            'type' => 'required',
            'target' => 'required',
            'content' => 'required|max:200',
        ];
    }
}