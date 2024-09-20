<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'property_types';

    protected $fillable = [
        'title_en',
        'title_ar',
        'slug_en',
        'slug_ar',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];


    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'property_type');
    }
}
