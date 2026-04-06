<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ApiResource(
    operations: [
        new GetCollection(middleware: ['auth:sanctum'], policy: 'viewAny'),
        new Get(middleware: ['auth:sanctum'], policy: 'view'),
        new Post(middleware: ['auth:sanctum'], policy: 'create', rules: StoreSubjectRequest::class),
        new Patch(middleware: ['auth:sanctum'], policy: 'update', rules: UpdateSubjectRequest::class),
        new Delete(middleware: ['auth:sanctum'], policy: 'delete'),
    ]
)]
class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'expected_hours',
        'school_id',
        'referential_path',
        'referential_name',
        'referential_size',
    ];

    protected function casts(): array
    {
        return [
            'expected_hours'   => 'integer',
            'referential_size' => 'integer',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'classroom_subject');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class)->orderBy('order');
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = $value;
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

    public function getExpectedHours()
    {
        return $this->expected_hours;
    }

    public function setExpectedHours($value)
    {
        $this->expected_hours = $value;
        return $this;
    }

    public function getSchoolId()
    {
        return $this->school_id;
    }

    public function setSchoolId($value)
    {
        $this->school_id = $value;
        return $this;
    }

    public function getReferentialPath()
    {
        return $this->referential_path;
    }

    public function setReferentialPath($value)
    {
        $this->referential_path = $value;
        return $this;
    }

    public function getReferentialName()
    {
        return $this->referential_name;
    }

    public function setReferentialName($value)
    {
        $this->referential_name = $value;
        return $this;
    }

    public function getReferentialSize()
    {
        return $this->referential_size;
    }

    public function setReferentialSize($value)
    {
        $this->referential_size = $value;
        return $this;
    }
}
