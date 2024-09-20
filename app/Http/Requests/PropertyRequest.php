<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
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
            'slug_en' => 'required|string|max:255|unique:property,slug_en',
            'slug_ar' => 'required|string|max:255|unique:property,slug_ar',
            'price' => 'required|numeric',
            'bed' => 'required|integer|min:0',
            'bath' => 'required|integer|min:0',
            'size' => 'required|numeric|min:0',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'highlights_en' => 'nullable|string',
            'highlights_ar' => 'nullable|string',
            'agent_id' => 'required|array',
            'agent_id.title_en' => 'required|string|max:255',
            'agent_id.title_ar' => 'required|string|max:255',
            'property_type' => 'required|array',
            'property_type.title_en' => 'required|string|max:255',
            'property_type.title_ar' => 'required|string|max:255',
            'property_for' => 'required|array',
            'property_for.title_en' => 'required|string|max:255',
            'property_for.title_ar' => 'required|string|max:255',
            'property_features' => 'required|array',
            'property_features.*.title_en' => 'required|string|max:255',
            'property_features.*.title_ar' => 'required|string|max:255',
            'property_features.*.description_en' => 'required|string|max:255',
            'property_features.*.description_ar' => 'required|string|max:255',
            'property_features.*.status' => 'required|integer',
            'property_near_location' => 'required|array',
            'property_near_location.*.location_en' => 'required|string|max:255',
            'property_near_location.*.location_ar' => 'required|string|max:255',
            'property_near_location.*.distance' => 'required|numeric',
            'sixty_tour' => 'nullable|url|max:255',
            'features_line_en' => 'nullable|string|max:255',
            'features_line_ar' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'map_link' => 'nullable|url|max:255',
            'dld_permit_number' => 'nullable|string|max:255',
            'status' => 'required|integer|in:0,1',
        ];
    }
}
