<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyFeatureAll extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'property_features_all';

    protected $fillable = [
        'property_id',
        'feature_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function feature()
    {
        return $this->belongsTo(PropertyFeature::class, 'feature_id');
    }
}
