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
            'title_en' => 'required|string|max:255|exists:property,title_en',
            'title_ar' => 'required|string|max:255|exists:property,title_ar',
            'slug_en' => 'required|string|max:255|exists:property,slug_en',
            'slug_ar' => 'required|string|max:255|exists:property,slug_ar',
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
            // 'agent_id.main_image' => 'required|url|max:255',
            'property_type' => 'required|array',
            'property_type.title_en' => 'required|string|max:255',
            'property_type.title_ar' => 'required|string|max:255',
            'property_for' => 'required|array',
            'property_for.title_en' => 'required|string|max:255',
            'property_for.title_ar' => 'required|string|max:255',
            'property_main_image' => 'required|url|max:255',
            'property_broucher' => 'nullable|url|max:255',
            'property_main_gallery' => 'required|array|min:3',
            'property_main_gallery.*' => 'required|url|max:255',
            'property_features' => 'required|array',
            'property_features.*.title_en' => 'required|string|max:255',
            'property_features.*.title_ar' => 'required|string|max:255',
            'property_features.*.description_en' => 'required|string|max:255',
            'property_features.*.description_ar' => 'required|string|max:255',
            'property_features.*.status' => 'required|integer',
            // 'property_features.*.feature_image' => 'required|url|max:255',
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

    /**
     * Custom messages for validation errors.
     *
     * @return array
    */
    public function messages()
    {
        return [
            'title_en.required' => 'The English title is required.',
            'title_en.string' => 'The English title must be a string.',
            'title_en.max' => 'The English title may not be greater than 255 characters.',
            'title_en.exists' => 'The selected English title is already exists.',
        
            'title_ar.required' => 'The Arabic title is required.',
            'title_ar.string' => 'The Arabic title must be a string.',
            'title_ar.max' => 'The Arabic title may not be greater than 255 characters.',
            'title_ar.exists' => 'The selected Arabic title is already exists.',
        
            'slug_en.required' => 'The English slug is required.',
            'slug_en.string' => 'The English slug must be a string.',
            'slug_en.max' => 'The English slug may not be greater than 255 characters.',
            'slug_en.exists' => 'The selected English slug is already exists.',
        
            'slug_ar.required' => 'The Arabic slug is required.',
            'slug_ar.string' => 'The Arabic slug must be a string.',
            'slug_ar.max' => 'The Arabic slug may not be greater than 255 characters.',
            'slug_ar.exists' => 'The selected Arabic slug is already exists.',
        ];
    }
}

