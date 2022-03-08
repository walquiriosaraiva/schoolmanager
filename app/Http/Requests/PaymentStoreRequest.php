<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'due_date' => 'required|date',
            'tuition' => 'required|string',
            'sdf' => 'required|string',
            'hot_lunch' => 'required|string',
            'enrollment' => 'required|string',
            'percentage_discount' => 'nullable',
            'type_of_payment' => 'nullable',
            'status_payment' => 'nullable',
            'contract_duration' => 'required',

            // Student information
            'student_id' => 'required'
        ];
    }
}
