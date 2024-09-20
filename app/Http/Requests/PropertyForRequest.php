<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyForRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'slug_en' => 'required|string|max:255|unique:property_for,slug_en,' . $id,
            'slug_ar' => 'required|string|max:255|unique:property_for,slug_ar,' . $id,
            'status' => 'required|integer',
        ];
    }
}
