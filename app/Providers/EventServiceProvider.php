<?php

namespace App\Providers;

use App\Models\Applicant;
use App\Models\Bond;
use App\Models\Course;
use App\Models\CourseClass;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\Pole;
use App\Models\Responsibility;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use App\Observers\ApplicantObserver;
use App\Observers\BondObserver;
use App\Observers\CourseClassObserver;
use App\Observers\CourseObserver;
use App\Observers\DocumentObserver;
use App\Observers\DocumentTypeObserver;
use App\Observers\EmployeeObserver;
use App\Observers\PoleObserver;
use App\Observers\ResponsibilityObserver;
use App\Observers\RoleObserver;
use App\Observers\UserObserver;
use App\Observers\UserTypeObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Applicant::observe(ApplicantObserver::class);
        Bond::observe(BondObserver::class);
        Course::observe(CourseObserver::class);
        CourseClass::observe(CourseClassObserver::class);
        Document::observe(DocumentObserver::class);
        DocumentType::observe(DocumentTypeObserver::class);
        Employee::observe(EmployeeObserver::class);
        Pole::observe(PoleObserver::class);
        Role::observe(RoleObserver::class);
        User::observe(UserObserver::class);
        UserType::observe(UserTypeObserver::class);
        Responsibility::observe(ResponsibilityObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return true;
    }
}
