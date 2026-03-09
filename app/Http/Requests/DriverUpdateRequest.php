<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name_driver'       => ['required','string','max:255'],
            'address_driver'    => ['nullable','string','max:255'],
            'phone_driver'      => ['nullable','regex:/^\d{10}$/'],
            'citizenid_driver'  => ['nullable','regex:/^\d{13}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_driver.regex'     => 'กรุณากรอกเบอร์โทร 10 หลัก (เฉพาะตัวเลข)',
            'citizenid_driver.regex' => 'กรุณากรอกเลขบัตรประชาชน 13 หลัก (เฉพาะตัวเลข)',
        ];
    }
}
