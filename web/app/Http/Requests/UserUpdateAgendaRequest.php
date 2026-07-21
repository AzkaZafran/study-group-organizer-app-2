<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateAgendaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "id_agenda" => ["required", "numeric"],
            "nama_agenda" => ["required", "max:50"],
            "lokasi_agenda" => ["required", "max:255"],
            "waktu_agenda" => ["required", "date_format:Y-m-d", "after_or_equal:today"],
            "jam_awal" => ["required", "date_format:H:i"],
            "jam_akhir" => ["required", "date_format:H:i", "after:jam_awal"]
        ];
    }
}
