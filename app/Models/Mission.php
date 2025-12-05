<?php

namespace App\Models;

use App\Enums\MissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Mission extends Model
{
    /** @use HasFactory<\Database\Factories\MissionFactory> */
    use HasFactory;

    protected $fillable = [
        'commercial_id',
        'title',
        'description',
        'location',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => MissionStatus::class,
        ];
    }

    /**
     * Get the commercial that owns the mission.
     */
    public function commercial(): BelongsTo
    {
        return $this->belongsTo(User::class, 'commercial_id');
    }

    /**
     * Get all tags for the mission.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get all applications for the mission.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Scope a query to only include active missions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', MissionStatus::Active);
    }
}
