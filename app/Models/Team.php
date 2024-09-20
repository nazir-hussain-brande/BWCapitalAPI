<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'team';

    protected $fillable = [
        'title_en',
        'title_ar',
        'designation_en',
        'designation_ar',
        'linkdin',
        'agent',
        'status',
    ];

    protected $casts = [
        'agent' => 'integer',
        'status' => 'integer',
    ];

    public function properties() : HasMany
    {
        return $this->hasMany(Property::class);
    }
}
