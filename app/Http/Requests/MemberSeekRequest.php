<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MemberSeekRequest extends Request
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
            'dp_val' => 'required|numeric',
            'pos_val' => 'required|numeric',
            'key' => 'min:1|max:20',
        ];
    }
}
