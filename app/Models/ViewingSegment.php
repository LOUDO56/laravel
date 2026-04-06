<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewingSegment extends Model
{
    protected $fillable = [
        'progress_id',
        'segment_token',
        'segment_start',
        'segment_end',
        'playback_rate',
        'validated_at',
    ];

    protected function casts(): array
    {
        return [
            'segment_start' => 'integer',
            'segment_end'   => 'integer',
            'playback_rate' => 'float',
            'validated_at'  => 'datetime',
        ];
    }

    public function progress(): BelongsTo
    {
        return $this->belongsTo(ViewingProgress::class, 'progress_id');
    }

    public function getProgressId()
    {
        return $this->progress_id;
    }

    public function setProgressId($value)
    {
        $this->progress_id = $value;
        return $this;
    }

    public function getSegmentToken()
    {
        return $this->segment_token;
    }

    public function setSegmentToken($value)
    {
        $this->segment_token = $value;
        return $this;
    }

    public function getSegmentStart()
    {
        return $this->segment_start;
    }

    public function setSegmentStart($value)
    {
        $this->segment_start = $value;
        return $this;
    }

    public function getSegmentEnd()
    {
        return $this->segment_end;
    }

    public function setSegmentEnd($value)
    {
        $this->segment_end = $value;
        return $this;
    }

    public function getPlaybackRate()
    {
        return $this->playback_rate;
    }

    public function setPlaybackRate($value)
    {
        $this->playback_rate = $value;
        return $this;
    }

    public function getValidatedAt()
    {
        return $this->validated_at;
    }

    public function setValidatedAt($value)
    {
        $this->validated_at = $value;
        return $this;
    }
}
