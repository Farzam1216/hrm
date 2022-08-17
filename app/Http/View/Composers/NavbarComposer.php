<?php

namespace App\Http\View\Composers;

use App\Domain\ACL\Models\Role;
use App\Domain\Approval\Actions\GetEmployeeNotifications;
use App\Domain\Benefit\Models\BenefitGroup;
use App\Domain\Benefit\Models\BenefitPlan;
use App\Domain\Benefit\Models\EmployeeBenefit;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Domain\Employee\Models\Asset;
use App\Domain\Employee\Models\Department;
use App\Domain\Employee\Models\Education;
use App\Domain\Employee\Models\EducationType;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeDocument;
use App\Domain\Employee\Models\EmployeeVisa;
use App\Domain\Employee\Models\Location;
use App\Domain\Employee\Models\SecondaryLanguage;
use App\Domain\Employee\Models\VisaType;
use App\Domain\Task\Models\EmployeeTask;
use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\TimeOffType;
use App\Domain\Attendance\Models\EmployeeAttendance;
use App\Traits\AccessibleFields;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App;
use App\Domain\PayRoll\Models\PayRoll;
use App\Domain\PayRoll\Models\PaySchedule;
use Illuminate\Database\Console\DumpCommand;

class NavbarComposer
{
    use AccessibleFields;

    /**
     * Create a new Navbar composer.
     *
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $navbar = $this->getNavItems();
        $isAdmin = Auth::user()->isAdmin();
        $locale = \request()->segment(1, '');
//        App::setLocale($locale);
        $employeeNotifications = (new GetEmployeeNotifications())->execute();
        $view->with('menu', $navbar)->with('isAdmin', $isAdmin)->with('locale', $locale)
            ->with('notificationCount', $employeeNotifications->notifications->count());
    }

    /**
     * @return $menu (array of navbar items)
     */
    public function getNavItems()
    {
        $menu = null;
        $user = Auth::user();
        //Check which menu items will be visible to authorized user, depending upon his/her permissions
        $menu = $this->myInfoTab($menu, $user);

        $menu = $this->settingsTab($menu, $user);

        $menu = $this->hiringTab($menu, $user);

        $menu = $this->performanceTab($menu, $user);

        $menu = $this->attendanceTab($menu, $user);

        $menu = $this->worksheduleTab($menu, $user);

        $menu = $this->payrollTab($menu, $user);

        $menu = $this->payScheduleTab($menu, $user);

        //Unauthorized Employee Tab
        if (session()->has('unauthorized_user')) {
            $menu = $this->unauthorizedEmployeeTab($menu, session('unauthorized_user'));
        }
        return $menu;
    }

    /**
     * Auth user has permissions associated with given models fields
     * @param $user
     * @param int $accessLevel
     * @param mixed ...$models
     * @return bool
     */
    public function isAllowed($user, $accessLevel = 0, ...$models)
    {
        foreach ($models as $model) {
            if (null !== $model::getPermissionsWithAccessLevel($user)) {
                $permissions = $model::getPermissionsWithAccessLevel($user);
                if ($this->authenticateAccessLevel($permissions, $accessLevel)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Given permissions has "All Employees" access level.
     * @param $user
     * @param mixed ...$permissions
     * @return bool
     */
    public function hasFullPermission($user, ...$permissions)
    {
        $accessLevelID = DB::table('access_levels')->where('name', 'All Employees')->pluck('id')->first();
        foreach ($permissions as $permission) {
            $allowedPermission = $user->getAllPermissions()->where('name', $permission)->where('pivot.access_level_id', $accessLevelID);
            if ($allowedPermission->isNotEmpty()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $permissions
     * @param $givenAccessLevel
     * @return bool
     */
    private function authenticateAccessLevel($permissions, $givenAccessLevel)
    {
        foreach ($permissions as $permission) {
            foreach ($permission as $accessLevel => $permissionsName) {
                //If $givenAccessLevel is 0 (self) then only if $accessLevel is 0, return true
                if ($givenAccessLevel == 0) {
                    if ($accessLevel == $givenAccessLevel) {
                        return true;
                    }
                } //If $givenAccessLevel is 1 then if $accessLevel is any option other than 0, return true
                else {
                    if ($accessLevel != 0) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /*
     * Auth User's personal information menu items
     */
    private function myInfoTab($menu, $user)
    {
        $menu['myInfo'] = null;
        if ($this->isAllowed(
            $user,
            0,
            Employee::class,
            Department::class,
            Location::class,
            EducationType::class,
            Education::class,
            SecondaryLanguage::class,
            Asset::class,
            VisaType::class,
            EmployeeVisa::class,
            EmployeeDocument::class,
            Asset::class,
            TimeOffType::class,
            Policy::class,
            EmployeeDependent::class,
            BenefitGroup::class,
            EmployeeBenefit::class,
            BenefitPlan::class,
            EmployeeAttendance::class,
        )) {
            $menu['my-info'] = [];
        }

        if (isset($menu['my-info'])) {
            //If My Info is visible => personal tab for My Info is always visible.
            $menu['my-info']['personal'] = true;
            //If My Info is visible => My Task tab for My Info is always visible.
            $menu['my-info']['tasks'] = true;
            if ($this->isAllowed(
                $user,
                0,
                EmployeeDocument::class
            )) {
                $menu['my-info']['documents'] = true;
            }
            if ($this->isAllowed(
                $user,
                0,
                Asset::class
            )) {
                $menu['my-info']['assets'] = true;
            }
            if ($this->isAllowed(
                $user,
                0,
                TimeOffType::class,
                Policy::class
            )) {
                $menu['my-info']['time-off'] = true;
            }
            //Check authorized user benefit dependents
            if ($this->isAllowed(
                $user,
                0,
                EmployeeDependent::class
            )) {
                $menu['my-info']['dependents'] = true;
            }
            //Check authorized user benefit details
            if ($this->isAllowed(
                $user,
                0,
                BenefitGroup::class,
                EmployeeBenefit::class,
                BenefitPlan::class
            )) {
                $menu['my-info']['benefits'] = true;
            }
             //Check authorized user attendance details
             if ($this->isAllowed(
                $user,
                0,
                EmployeeAttendance::class
            )) {
                $menu['my-info']['attendance'] = true;
            }
        }
        return $menu;
    }

    /**
     * What auth user can see in settings tab
     * @param $menuItems
     * @param $user
     * @return mixed
     */
    private function settingsTab($menuItems, $user)
    {
        //Create Time off types + policies and assign to different employees
        if ($this->hasFullPermission(
            $user,
            "manage setting time_off"
        )) {
            $menuItems['settings']['time-off'] = true;
        }

        //Manage Benefit Settings: create benefit plans and groups + assign to employees
        if ($this->hasFullPermission(
            $user,
            "manage setting benefit"
        )) {
            $menuItems['settings']['benefits'] = true;
        }
        //Setup job questions, email templates and job types etc
        if ($this->hasFullPermission($user, "manage setting hiring")) {
            $menuItems['settings']['hiring'] = true;
        }

        //Create or edit task categories and tasks (offboarding and onboarding) for all employees
        if ($this->hasFullPermission($user, "manage setting offboarding", "manage setting onboarding")) {
            $menuItems['settings']['tasks'] = true;
        }

        //Manage Approvals Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting approval"
        )) {
            $menuItems['settings']['approvals'] = true;
        }

        //Manage education type Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting education type"
        )) {
            $menuItems['settings']['education type'] = true;
        }

        //Manage asset type Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting asset type"
        )) {
            $menuItems['settings']['asset type'] = true;
        }

        //Manage visa type Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting visa type"
        )) {
            $menuItems['settings']['visa type'] = true;
        }

        //Manage document Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting document"
        )) {
            $menuItems['settings']['document'] = true;
        }

        //Manage document type Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting document type"
        )) {
            $menuItems['settings']['document type'] = true;
        }

        //Manage location Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting location"
        )) {
            $menuItems['settings']['location'] = true;
        }

        //Manage department Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting department"
        )) {
            $menuItems['settings']['department'] = true;
        }

        //Manage employment status Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting employment status"
        )) {
            $menuItems['settings']['employment status'] = true;
        }

        //Manage designation Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting designation"
        )) {
            $menuItems['settings']['designation'] = true;
        }

        //Manage division Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting division"
        )) {
            $menuItems['settings']['division'] = true;
        }

        //Manage language Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting language"
        )) {
            $menuItems['settings']['language'] = true;
        }

        //Manage secondary language Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting secondary language"
        )) {
            $menuItems['settings']['secondary language'] = true;
        }

        //Manage compensation Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting compensation"
        )) {
            $menuItems['settings']['compensation'] = true;
        }

        //Manage Smtp Details Settings
        if ($this->hasFullPermission(
            $user,
            "manage setting smtp details"
        )) {
            $menuItems['settings']['smtp'] = true;
        }

        return $menuItems;
    }

    /**
     *
     * Check if Auth User has permissions to manage hiring process
     * @param $menu
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @return mixed
     */
    private function hiringTab($menu, ?\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        //Create or Edit Job + Candidates
        if ($this->hasFullPermission($user, "manage hiring jobopening_candidates")) {
            $menu['hiring'] = true;
        }
        return $menu;
    }

    /**
     *
     * Check if Auth User has permissions to manage hiring process
     * @param $menu
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @return mixed
     */
    private function performanceTab($menu, ?\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        //Create or Edit Job + Candidates
        if ($this->hasFullPermission($user, "manage performance review")) {
            $menu['performance'] = true;
        }
        return $menu;
    }

    private function attendanceTab($menu, ?\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        if ($this->hasFullPermission($user, "manage employees attendance")) {
            $menu['attendance'] = true;
        }
        return $menu;
    }

    private function worksheduleTab($menu, ?\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        if ($this->hasFullPermission($user, "manage employees work_schedule")) {
            $menu['workShedule'] = true;
        }
        return $menu;
    }

    private function payrollTab($menu, ?\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        if ($this->hasFullPermission($user, "manage payroll management")) {
            $menu['payroll'] = true;
        }
        return $menu;
    }

    private function payScheduleTab($menu, ?\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        if ($this->hasFullPermission($user, "manage pay schedule")) {
            $menu['paySchedule'] = true;
        }
        return $menu;
    }

    /**
     * Auth user's permissions against currently opened employee
     * @param $menuItems
     * @param $AuthUser
     * @param $employee
     * @return mixed
     */
    private function unauthorizedEmployeeTab($menuItems, $employee)
    {
        $menuItems['unAuthUser'] = null;
        $employeeInfoModels = [
            Employee::class, Department::class, Location::class, EducationType::class, Education::class,
            SecondaryLanguage::class, Asset::class, VisaType::class, EmployeeVisa::class, EmployeeDocument::class,
            Asset::class, TimeOffType::class, Policy::class, EmployeeDependent::class, BenefitGroup::class,
            EmployeeBenefit::class, BenefitPlan::class, EmployeeTask::class
        ];
        foreach ($employeeInfoModels as $employeeInfoModel) {
            $permissions = $this->getAccessibleFieldList($employeeInfoModel, [$employee]);
            if (!empty($permissions)) {
                $menuItems['unAuthUser'] = [];
                break;
            }
        }
        if (isset($menuItems['unAuthUser'])) {
            //If Unauthorized Employee Tab is visible => personal tab will also be visible.
            $menuItems['unAuthUser']['personal'] = true;
            if (!empty($this->getAccessibleFieldList(EmployeeTask::class, [$employee]))) {
                $menuItems['unAuthUser']['tasks'] = true;
            }
            if (!empty($this->getAccessibleFieldList(EmployeeDocument::class, [$employee]))) {
                $menuItems['unAuthUser']['documents'] = true;
            }
            if (!empty($this->getAccessibleFieldList(Asset::class, [$employee]))) {
                $menuItems['unAuthUser']['assets'] = true;
            }
            if (
                !empty($this->getAccessibleFieldList(TimeOffType::class, [$employee])) ||
                !empty($this->getAccessibleFieldList(Policy::class, [$employee]))
            ) {
                $menuItems['unAuthUser']['time-off'] = true;
            }
            //Check un-authorized user benefit dependents
            if (!empty($this->getAccessibleFieldList(EmployeeDependent::class, [$employee]))) {
                $menuItems['unAuthUser']['dependents'] = true;
            }
            //Check un-authorized user benefit details
            if (
                !empty($this->getAccessibleFieldList(BenefitGroup::class, [$employee])) ||
                !empty($this->getAccessibleFieldList(EmployeeBenefit::class, [$employee])) ||
                !empty($this->getAccessibleFieldList(BenefitPlan::class, [$employee]))
            ) {
                $menuItems['unAuthUser']['benefits'] = true;
            }
            $employeeRoles = Employee::find(auth()->user()->id)->roles;
            foreach($employeeRoles as $role)
            {
                if($role->type == 'custom'){
                    $menuItems['unAuthUser']['attendance'] = true;
                    $menuItems['unAuthUser']['payroll'] = true;
                }
            }
        }
        return $menuItems;
    }
}
