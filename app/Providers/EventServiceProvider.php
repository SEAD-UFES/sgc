<?php

namespace App\Providers;

use App\Models\Approved;
use App\Models\ApprovedState;
use App\Models\Bond;
use App\Models\BondDocument;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\Gender;
use App\Models\GrantType;
use App\Models\MaritalStatus;
use App\Models\Pole;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use App\Models\UserType;
use App\Models\Responsibility;
use App\Observers\ApprovedObserver;
use App\Observers\ApprovedStateObserver;
use App\Observers\BondDocumentObserver;
use App\Observers\BondObserver;
use App\Observers\CourseObserver;
use App\Observers\CourseTypeObserver;
use App\Observers\DocumentObserver;
use App\Observers\DocumentTypeObserver;
use App\Observers\EmployeeDocumentObserver;
use App\Observers\EmployeeObserver;
use App\Observers\GenderObserver;
use App\Observers\GrantTypeObserver;
use App\Observers\MaritalStatusObserver;
use App\Observers\PoleObserver;
use App\Observers\RoleObserver;
use App\Observers\StateObserver;
use App\Observers\UserObserver;
use App\Observers\ResponsibilityObserver;
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
        Approved::observe(ApprovedObserver::class);
        ApprovedState::observe(ApprovedStateObserver::class);
        Bond::observe(BondObserver::class);
        BondDocument::observe(BondDocumentObserver::class);
        Course::observe(CourseObserver::class);
        CourseType::observe(CourseTypeObserver::class);
        Document::observe(DocumentObserver::class);
        DocumentType::observe(DocumentTypeObserver::class);
        Employee::observe(EmployeeObserver::class);
        EmployeeDocument::observe(EmployeeDocumentObserver::class);
        Gender::observe(GenderObserver::class);
        GrantType::observe(GrantTypeObserver::class);
        MaritalStatus::observe(MaritalStatusObserver::class);
        Pole::observe(PoleObserver::class);
        Role::observe(RoleObserver::class);
        State::observe(StateObserver::class);
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
