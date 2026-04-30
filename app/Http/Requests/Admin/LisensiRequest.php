<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LisensiRequest extends FormRequest
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
            'akun_id'          => 'required|exists:akun,id',
            'paket_id'         => 'required|exists:paket,id',
            'tipe'             => 'required|in:lifetime,subscription',
            'tanggal_mulai'    => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after:tanggal_mulai',
            'catatan'          => 'nullable|string',
        ];
    }
}
