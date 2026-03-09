<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TruckUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $yearMax = (int) now()->year;
        $current = $this->route('truck'); // จะได้ค่าเป็น id_truck

        return [
            'id_truck' => [
                'bail',
                'required',
                'string',
                'max:45',
                'regex:/^[ก-ฮA-Za-z0-9\s\-]+$/u',
                Rule::unique('trucks', 'id_truck')
                    ->ignore($current, 'id_truck')
                    ->whereNull('deleted_at'),
            ],
            'brand_truck'       => ['bail', 'required', 'string', 'max:255'],
            'model_truck'       => ['nullable', 'string', 'max:255'],
            'year_truck'        => ['nullable', 'integer', "between:1980,$yearMax"],
            'weight_truck'      => ['nullable', 'integer', 'min:0'],
            'fuelfactory_truck' => ['nullable', 'integer', 'min:0'],
            'status_truck'      => ['bail', 'required', Rule::in(['active', 'maintenance', 'retired'])],
            'province_truck' => [
                'bail',
                'required',
                'string',
                'max:50',
                'regex:/^[\x{0E00}-\x{0E7F}\s]+$/u',
            ],


            'fuel_rate' => [
                'bail',
                'required',
                'numeric',
                'min:0.1',
                'max:50',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id_truck' => strtoupper(trim((string) $this->input('id_truck'))),
        ]);
    }
}
