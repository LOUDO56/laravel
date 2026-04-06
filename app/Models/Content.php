<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\UpdateContentRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ApiResource(
    operations: [
        new GetCollection(middleware: ['auth:sanctum'], policy: 'viewAny'),
        new Get(middleware: ['auth:sanctum'], policy: 'view'),
        new Post(middleware: ['auth:sanctum'], policy: 'create', rules: StoreContentRequest::class),
        new Patch(middleware: ['auth:sanctum'], policy: 'update', rules: UpdateContentRequest::class),
        new Delete(middleware: ['auth:sanctum'], policy: 'delete'),
    ]
)]
class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'teacher_id',
        'title',
        'description',
        'video_url',
        'duration_seconds',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'duration_seconds' => 'integer',
            'order'            => 'integer',
        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function viewingProgress(): HasMany
    {
        return $this->hasMany(ViewingProgress::class);
    }

    public function getSubjectId()
    {
        return $this->subject_id;
    }

    public function setSubjectId($value)
    {
        $this->subject_id = $value;
        return $this;
    }

    public function getTeacherId()
    {
        return $this->teacher_id;
    }

    public function setTeacherId($value)
    {
        $this->teacher_id = $value;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($value)
    {
        $this->title = $value;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
        return $this;
    }

    public function getVideoUrl()
    {
        return $this->video_url;
    }

    public function setVideoUrl($value)
    {
        $this->video_url = $value;
        return $this;
    }

    public function getDurationSeconds()
    {
        return $this->duration_seconds;
    }

    public function setDurationSeconds($value)
    {
        $this->duration_seconds = $value;
        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($value)
    {
        $this->order = $value;
        return $this;
    }
}
