<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyFeatureRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'status' => 'required|integer',
        ];
    }

    public function withValidator($validator)
    {
        $id = $this->route('id');

        if ($id) {
            $validator->after(function ($validator) use ($id) {
                $validator->setRules([
                    'slug_en' => 'required|string|max:255|unique:property_features,slug_en,' . $id,
                    'slug_ar' => 'required|string|max:255|unique:property_features,slug_ar,' . $id,
                ]);
            });
        } else {
            $validator->setRules([
                'slug_en' => 'required|string|max:255|unique:property_features,slug_en',
                'slug_ar' => 'required|string|max:255|unique:property_features,slug_ar',
            ]);
        }
    }
}
