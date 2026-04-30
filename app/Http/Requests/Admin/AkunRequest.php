<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AkunRequest extends FormRequest
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
        $akunId = $this->route('akun')?->id;
        return [
            'nama'       => 'required|string|max:100',
            'email'      => ['required', 'email', Rule::unique('akun', 'email')->ignore($akunId)],
            'telepon'    => 'nullable|string|max:20',
            'nama_toko'  => 'nullable|string|max:100',
            'password'   => $this->isMethod('POST') ? 'required|string|min:6' : 'nullable|string|min:6',
            'aktif'      => 'boolean',
        ];
    }
}
