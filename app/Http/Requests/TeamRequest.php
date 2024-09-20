<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'designation_en' => 'required|string|max:255',
            'designation_ar' => 'required|string|max:255',
            'linkdin' => 'nullable|url|max:255',
            'agent' => 'required|integer',
            'status' => 'required|integer',
        ];
    }

    public function withValidator($validator)
    {
        $id = $this->route('id');

        if ($id) {
            $validator->after(function ($validator) use ($id) {
                $validator->setRules([
                    'title_en' => 'required|string|max:255|unique:team,title_en,' . $id,
                    'title_ar' => 'required|string|max:255|unique:team,title_ar,' . $id,
                    'linkdin' => 'required|string|max:255|unique:team,linkdin,' . $id,
                ]);
            });
        }
    }
}
