<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'letter_name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'letter_date' => ['required', 'date'],
            'letter_from' => ['required', 'string', 'max:255'],
            'letter_send_to' => ['required', 'string', 'max:255'],
            'letter_subject' => ['required', 'string', 'max:255'],
        ];

        // Tambahkan aturan validasi file berdasarkan metode HTTP
        if ($this->isMethod('post')) {
            // Untuk create (store)
            $rules['file'] = ['required', 'file', 'mimes:pdf'];
        } else {
            // Untuk update
            $rules['file'] = ['nullable', 'file', 'mimes:pdf'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'letter_name.required' => 'Nama surat harus diisi',
            'type.required' => 'Jenis surat harus diisi',
            'letter_date.required' => 'Tanggal surat harus diisi',
            'letter_from.required' => 'Asal surat harus diisi',
            'letter_send_to.required' => 'Tujuan surat harus diisi',
            'letter_subject.required' => 'Perihal surat harus diisi',
            'file.required' => 'File surat harus diisi',
            'letter_date.date' => 'Format tanggal salah',
            'letter_name.max' => 'Nama surat maksimal 255 karakter',
            'type.max' => 'Jenis surat maksimal 255 karakter',
            'letter_from.max' => 'Asal surat maksimal 255 karakter',
            'letter_send_to.max' => 'Tujuan surat maksimal 255 karakter',
            'letter_subject.max' => 'Perihal surat maksimal 255 karakter',
            'file.mimes' => 'File surat harus berformat PDF',
        ];
    }
}
