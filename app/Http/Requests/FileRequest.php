<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */
    public function rules(): array
    {
        return [
            'path' => 'nullable|string',
            'ref_id' => 'required|integer',
            'ref_point' => 'required|string',
            'alt_text' => 'required|string',
            'image' => 'required|file|image|max:10240'
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array
    */
    public function messages()
    {
        return [
            'image.required' => 'The image is required.',
            'image.file' => 'The image must be a valid file.',
            'image.image' => 'The file must be an image.',
            'image.max' => 'The image may not be greater than 10 MB.',
            'path.string' => 'The image path must be a string.',
            'ref_id.required' => 'The reference ID is required.',
            'ref_point.required' => 'The reference point is required.',
            'alt_text.required' => 'The alt text is required.',
        ];
    }
}
