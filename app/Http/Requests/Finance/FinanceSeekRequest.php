<?php

namespace App\Http\Requests\Finance;

use App\Http\Requests\Request;

class FinanceSeekRequest extends Request
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
            'seekName' => 'min:1|max:20',
            'seekDp' => 'min:1|max:20',
        ];
    }
}