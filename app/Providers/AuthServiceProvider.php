<?php

namespace App\Providers;

use App\Domain\Benefit\Models\EmployeeBenefit;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Domain\Employee\Models\Asset;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeDocument;
use App\Domain\Task\Models\EmployeeTask;
use App\Domain\TimeOff\Models\TimeOffType;
use App\Policies\AssetPolicy;
use App\Policies\EmployeeBenefitPolicy;
use App\Policies\EmployeeDependentPolicy;
use App\Policies\EmployeeDocumentPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\EmployeeTaskPolicy;
use App\Policies\TImeOffTypePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Employee::class => EmployeePolicy::class,
        EmployeeDocument::class =>  EmployeeDocumentPolicy::class,
        Asset::class => AssetPolicy::class,
        TimeOffType::class => TImeOffTypePolicy::class,
        EmployeeBenefit::class =>  EmployeeBenefitPolicy::class,
        EmployeeDependent::class => EmployeeDependentPolicy::class,
        EmployeeTask::class => EmployeeTaskPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();

        //Assign All default permissions to admin

        Gate::before(function ($user, $ability) {
            return $user->hasRole('Admin') ? true : null;
        });
    }
}
