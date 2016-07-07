<?php

namespace App\Http\Requests\Finance;

use App\Http\Requests\Request;

class FinanceOutRequest extends Request
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
            'out_amount' => 'required|numeric|min:1',
            'out_item' => 'required|min:1|max:20',
            'out_date' => 'required',
            'out_bill' => 'required',
            'out_about' => 'required',
        ];
    }
}