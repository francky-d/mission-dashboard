<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ConsultantProfile extends Model
{
    /** @use HasFactory<\Database\Factories\ConsultantProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'cv_url',
        'experience_years',
    ];

    /**
     * Get the user that owns the consultant profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all tags for the consultant profile.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
