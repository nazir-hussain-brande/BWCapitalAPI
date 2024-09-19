<?php

namespace App\Http\Requests;

use App\Models\Team;
use App\Models\PropertyFor;
use App\Models\PropertyType;
use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
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
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'slug_en' => 'nullable|string|max:255',
            'slug_ar' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'bed' => 'required|numeric',
            'bath' => 'required|numeric',
            'size' => 'required|numeric',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'highlights_en' => 'nullable|string',
            'highlights_ar' => 'nullable|string',
            'agent_id' => 'required|integer|in:' . $this->getValidTeamAgentIds(),
            'property_type' => 'required|integer|in:'. $this->getValidPropertyTypeIds(),
            'property_for' => 'required|integer|in:' . $this->getValidPropertyForIds(),
            'sixty_tour' => 'nullable|string|max:255',
            'features_line_en' => 'nullable|string|max:255',
            'features_line_ar' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'map_link' => 'nullable|string|max:255',
            'dld_permit_number' => 'nullable|string|max:255',
            'status' => 'required|integer',
        ];
    }

    protected function getValidTeamAgentIds()
    {
        return Team::where('status', 1)->where('agent', 1)->pluck('id')->implode(',');
    }

    protected function getValidPropertyTypeIds()
    {
        return PropertyType::where('status',1)->pluck('id')->implode(',');
    }

    protected function getValidPropertyForIds()
    {
        return PropertyFor::where('status', 1)->pluck('id')->implode(',');
    }

}
