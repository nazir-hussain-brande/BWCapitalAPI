<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyFeature extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'property_features';

    protected $fillable = [
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
        'status',
    ];

    protected $dates = [
        'deleted_at',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
