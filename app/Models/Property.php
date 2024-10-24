<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'property';

    protected $fillable = [
        'title_en',
        'title_ar',
        'slug_en',
        'slug_ar',
        'seo_subject_line',
        'price',
        'bed',
        'bath',
        'size',
        'description_en',
        'description_ar',
        'highlights_en',
        'highlights_ar',
        'agent_id',
        'property_type',
        'property_for',
        'sixty_tour',
        'features_line_en',
        'features_line_ar',
        'location',
        'map_link',
        'dld_permit_number',
        'status',
    ];

    protected $casts = [
        'price' => 'double',
        'bed' => 'double',
        'bath' => 'double',
        'size' => 'double',
        'status' => 'integer',
    ];

    protected $dates = [
        'deleted_at',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function files() : HasMany
    {
        return $this->hasMany(File::class, 'ref_id');
    }

    public function nearLocations() : HasMany
    {
        return $this->hasMany(PropertyNearLocation::class, 'property_id');
    }

    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class, 'property_type');
    }

    public function propertyFor(): BelongsTo
    {
        return $this->belongsTo(PropertyFor::class, 'property_for');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'agent_id');
    }

    public function propertyFeatures(): BelongsToMany
    {
        return $this->belongsToMany(PropertyFeature::class, 'property_features_all', 'property_id', 'feature_id');
    }
}
