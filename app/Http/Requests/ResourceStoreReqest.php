<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ResourceStoreReqest extends Request
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
                'name'    => 'required|max:30',
                'unit'    => 'required|numeric',
                'notice'  => 'numeric',
                'alert'   => 'numeric',
                'type'    => 'required|numeric',
                'content' => 'max:200',
        ];
    }
}

