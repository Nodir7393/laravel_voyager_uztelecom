<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportDateRequest extends FormRequest
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
            'date' => 'nullable',
            'date_2' => 'nullable',
            'date_10' => 'nullable',
            'date_5' => 'nullable',
            'date_6' => 'nullable',
            'date_9' => 'nullable',
            'date_3_month' => 'nullable',
            'date_4' => 'nullable',
        ];
    }
}
