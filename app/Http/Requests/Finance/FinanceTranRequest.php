<?php

namespace App\Http\Requests\Finance;

use App\Http\Requests\Request;

class FinanceTranRequest extends Request
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
            'tran_amount' => 'required|numeric|min:1',
            'tran_item' => 'required|min:1|max:20',
            'tran_date' => 'required',
            'tran_type' => 'required',
            'tran_from' => 'required',
            'tran_to' => 'required',
        ];
    }
}