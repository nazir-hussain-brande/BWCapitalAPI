<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'files';

    protected $fillable = [
        'name',
        'path',
        'ref_id',
        'ref_point',
        'alt_text',
    ];

    protected $dates = [
        'deleted_at',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function property() : BelongsTo
    {
        return $this->belongsTo(Property::class, 'id', 'ref_id');
    }
}
