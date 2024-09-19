<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyImageUploadRequest extends FormRequest
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
            'ref_id' => 'required|integer|exists:property,id',
            'ref_point' => 'required|in:property_main_image,property_broucher,property_main_gallery,property_features_gallery',
            'alt_text' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'ref_id.required' => 'The property ID is required.',
            'ref_id.exists' => 'The selected property ID is invalid.',
            'ref_point.required' => 'The reference point is required.',
            'ref_point.in' => 'The reference point must be one of the following: property_main_image, property_broucher, property_main_gallery, property_features_gallery.',
            'alt_text.string' => 'The alt text must be a string.',
            'alt_text.max' => 'The alt text may not be greater than 255 characters.',
            'image.required' => 'An image is required.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 10MB.',
        ];
    }
}
