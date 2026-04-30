<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AktivasiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'license_key' => 'required|string',
            'device_id'   => 'required|string',
            'nama_device' => 'nullable|string',
            'os'          => 'nullable|string',
            'hostname'    => 'nullable|string',
        ];
    }
}
