<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentStoreRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'gender' => 'required|string',
            'nationality' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'address2' => 'nullable|string',
            'city' => 'required|string',
            'zip' => 'required|string',
            'photo' => 'nullable|string',
            'birthday' => 'required|date',
            'ethnicity' => 'required|string',
            'application_grade' => 'required|string',
            'blood_type' => 'required|string',
            'password' => 'required|string|min:8',
            'contract' => 'nullable',
            'ticket' => 'nullable',

            // Parents' information
            'father_name' => 'required|string',
            'father_phone' => 'required|string',
            'mother_name' => 'required|string',
            'mother_phone' => 'required|string',
            'parent_address' => 'required|string',
            'cpf' => 'nullable|cpf',
            'passport' => 'nullable|string',

            // Academic information
            'class_id' => 'required',
            'section_id' => 'required',
            'board_reg_no' => 'string',
            'session_id' => 'required',
            'id_card_number' => 'required',

            // Student information
            'due_date' => 'required|date',
            'tuition' => 'required|string',
            'sdf' => 'required|string',
            'hot_lunch' => 'required|string',
            'enrollment' => 'required|string',
            'percentage_discount' => 'nullable',
            'type_of_payment' => 'nullable',
            'status_payment' => 'nullable',
            'contract_duration' => 'required',
            'student_id' => 'nullable',

            // Documents
            'transcript' => 'nullable',
            'student_identidade' => 'nullable',
            'vaccination_record' => 'nullable',
            'digital_student_photo' => 'nullable',
            'primary_parent_passport' => 'nullable',
            'outros_documentos' => 'nullable',
        ];
    }
}
