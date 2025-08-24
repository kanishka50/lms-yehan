<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DigitalProductRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_featured' => 'boolean',
        ];

        // When creating a new product, PDF is required
        if ($this->isMethod('post')) {
            $rules['pdf_file'] = 'required|file|mimes:pdf|max:51200'; // 50MB max
        } else {
            // When updating, PDF is optional
            $rules['pdf_file'] = 'nullable|file|mimes:pdf|max:51200'; // 50MB max
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'pdf_file.required' => 'Please upload a PDF file.',
            'pdf_file.mimes' => 'The file must be a PDF.',
            'pdf_file.max' => 'The PDF file size must not exceed 50MB.',
        ];
    }
}