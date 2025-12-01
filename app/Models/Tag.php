<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get all consultant profiles that have this tag.
     */
    public function consultantProfiles(): MorphToMany
    {
        return $this->morphedByMany(ConsultantProfile::class, 'taggable');
    }

    /**
     * Get all missions that have this tag.
     */
    public function missions(): MorphToMany
    {
        return $this->morphedByMany(Mission::class, 'taggable');
    }
}
