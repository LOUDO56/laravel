<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ApiResource(
    operations: [
        new GetCollection(middleware: ['auth:sanctum'], policy: 'viewAny'),
        new Get(middleware: ['auth:sanctum'], policy: 'view'),
        new Post(middleware: ['auth:sanctum'], policy: 'create', rules: StoreClassroomRequest::class),
        new Patch(middleware: ['auth:sanctum'], policy: 'update', rules: UpdateClassroomRequest::class),
        new Delete(middleware: ['auth:sanctum'], policy: 'delete'),
    ]
)]
class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_id',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_user')
                    ->withPivot('role_in_class');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_user')
                    ->wherePivot('role_in_class', 'student')
                    ->withPivot('role_in_class');
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_user')
                    ->wherePivot('role_in_class', 'teacher')
                    ->withPivot('role_in_class');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'classroom_subject');
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

    public function getSchoolId()
    {
        return $this->school_id;
    }

    public function setSchoolId($value)
    {
        $this->school_id = $value;
        return $this;
    }
}
