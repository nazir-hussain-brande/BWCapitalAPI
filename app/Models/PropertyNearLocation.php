<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyNearLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'property_near_locations';

    protected $fillable = [
        'location_en',
        'location_ar',
        'distance',
        'property_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public function property() : BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
