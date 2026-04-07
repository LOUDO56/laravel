<?php

namespace App\Providers;

use App\Contracts\SubjectReferentialServiceInterface;
use App\Contracts\TrackingServiceInterface;
use App\Models\Classroom;
use App\Models\Content;
use App\Models\School;
use App\Models\Subject;
use App\Models\User;
use App\Models\ViewingProgress;
use App\Policies\ClassroomPolicy;
use App\Policies\ContentPolicy;
use App\Policies\SchoolPolicy;
use App\Policies\SubjectPolicy;
use App\Policies\UserPolicy;
use App\Policies\ViewingProgressPolicy;
use App\Services\SubjectReferentialService;
use App\Services\TrackingService;
use App\Observers\ContentObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TrackingServiceInterface::class, TrackingService::class);
        $this->app->bind(SubjectReferentialServiceInterface::class, SubjectReferentialService::class);
    }

    public function boot(): void
    {
        // Policies
        Gate::policy(School::class,          SchoolPolicy::class);
        Gate::policy(Classroom::class,       ClassroomPolicy::class);
        Gate::policy(Subject::class,         SubjectPolicy::class);
        Gate::policy(Content::class,         ContentPolicy::class);
        Gate::policy(ViewingProgress::class, ViewingProgressPolicy::class);
        Gate::policy(User::class,            UserPolicy::class);

        // Observers
        Content::observe(ContentObserver::class);

        // Blade directives
        Blade::directive('role', function (string $role) {
            return "<?php if(auth()->check() && auth()->user()->role->value === $role): ?>";
        });
        Blade::directive('endrole', function () {
            return '<?php endif; ?>';
        });
    }
}
