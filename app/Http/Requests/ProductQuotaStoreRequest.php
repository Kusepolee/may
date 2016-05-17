<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductQuotaStoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => 'required|max:32',
            'resource'  => 'numeric',
            'amount'    => 'numeric',
            'type'      => 'numeric',
            'time'      => 'numeric',
            'work_type' => 'numeric',
        ];
    }
}
