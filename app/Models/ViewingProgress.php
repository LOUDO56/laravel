<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ApiResource(
    operations: [
        new GetCollection(middleware: ['auth:sanctum'], policy: 'viewAny'),
        new Get(middleware: ['auth:sanctum'], policy: 'view'),
    ]
)]
class ViewingProgress extends Model
{
    protected $table = 'viewing_progress';

    protected $fillable = [
        'user_id',
        'content_id',
        'watched_seconds',
        'last_position',
        'completed',
    ];

    protected function casts(): array
    {
        return [
            'completed'       => 'boolean',
            'watched_seconds' => 'integer',
            'last_position'   => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function segments(): HasMany
    {
        return $this->hasMany(ViewingSegment::class, 'progress_id');
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($value)
    {
        $this->user_id = $value;
        return $this;
    }

    public function getContentId()
    {
        return $this->content_id;
    }

    public function setContentId($value)
    {
        $this->content_id = $value;
        return $this;
    }

    public function getWatchedSeconds()
    {
        return $this->watched_seconds;
    }

    public function setWatchedSeconds($value)
    {
        $this->watched_seconds = $value;
        return $this;
    }

    public function getLastPosition()
    {
        return $this->last_position;
    }

    public function setLastPosition($value)
    {
        $this->last_position = $value;
        return $this;
    }

    public function getCompleted()
    {
        return $this->completed;
    }

    public function setCompleted($value)
    {
        $this->completed = $value;
        return $this;
    }
}
