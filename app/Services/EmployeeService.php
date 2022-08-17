<?php

namespace App\Services;

use App;
use App\Domain\Approval\Actions\CompareRequestedFieldWithFillable;
use App\Domain\Approval\Actions\SendNotificationForInformationUpdate;
use App\Domain\Approval\Actions\StoreApprovalForEmployeeRole;
use App\Domain\Approval\Models\ApprovalRequestedDataField;
use App\Domain\Benefit\Models\BenefitGroup;
use App\Domain\Benefit\Models\BenefitPlan;
use App\Domain\Benefit\Models\EmployeeBenefit;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Domain\Employee\Models\Employee;
use App\Domain\TimeOff\Models\LeaveType;
use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\TimeOffType;
use App\Mail\SlackInvitationMail;
use App\Mail\UpdateAccount;
use App\Mail\ZohoInvitationMail;
use App\Models\ACL\Role;
use App\Models\Asset;
use App\Models\AssetsType;
use App\Models\Department;
use App\Models\Employee\DocumentType;
use App\Models\Employee\Education;
use App\Models\Employee\EducationType;
use App\Models\Employee\EmployeeBankAccount;
use App\Models\Employee\EmployeeDocument;
use App\Models\Employee\EmployeeVisa;
use App\Models\Employee\EmploymentStatus;
use App\Models\Employee\SecondaryLanguage;
use App\Models\Employee\VisaType;
use App\Models\Location;
use App\Models\Note;
use App\Models\Tasks\EmployeeTask;
use App\Services\AsanaService as AsanaService;
use App\Services\SlackService as SlackService;
use App\Services\ZohoService as ZohoService;
use App\Traits\AccessibleFields;
use App\Traits\SendNotification;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Mail;
use Session;

class EmployeeService
{
    use AccessibleFields;
    use SendNotification;
    public $designations = [
        'ceo' => 'CEO',
        'project_coordinator' => 'Project Coordinator',
        'web_developer' => 'Web Developer',
        'junior_web_developer' => 'Junior Web Developer',
        'front_end_developer' => 'Front-end Developer',
        'account_sales_executive' => 'Account Sales Executive',
        'sales_officer' => 'Sales Officer',
        'digital_marketing_executive' => 'Digital Marketing Executive',
        'content_writer' => 'Content Writer',
        'digital_marketer' => 'Digital Marketer',
        'web_designer_lead' => 'Web Designer Lead',
        'junior_web_designer' => 'Junior Web Designer',
        'hr_manager' => 'HR Manager',
        'hr_officer' => 'HR Officer',
        'admin' => 'Admin',
    ];

    /**
     * @param string $id
     *
     * @return array
     */
    public function showEmployeeInformation($id = '')
    {
        $data = [];
        if ('all' == $id) {
            $data['employee'] = Employee::with('Location', 'department', 'employmentStatus', 'employeeManager')->get();
        } elseif ($id == '') {
            $data['employee'] = Employee::with('Location', 'department', 'employmentStatus')->where(
                'status',
                '!=',
                '0'
            )->where('id', '<>', Auth::user()->id)->get();
        } else {
            $data['employee'] = Employee::with('Location', 'department', 'employmentStatus')
                ->where('employment_status_id', $id)
                ->get();
        }
        $data['active_employees'] = Employee::where('status', '1')->count();
        $data['filter'] = EmploymentStatus::all();

        return $data;
    }

    /**
     * @param $data
     * @param $locale
     *
     * @return mixed
     *
     * @throws GuzzleException
     */
    public function storeEmployeeInformation($data, $locale)
    {
        //token get from values.php in config folder
        $token = config('values.SlackToken');
        $when = Carbon::now()->addMinutes(10);
        $l = 8;
        $password = bcrypt('123456');
        $requestedData = [
            'gender' => $data['gender'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'contact_no' => $data['contact_no'],
            'emergency_contact' => $data['emergency_contact'],
            'emergency_contact_relationship' => $data['emergency_contact_relationship'],
            'password' => $password,
            'official_email' => $data['official_email'],
            'personal_email' => $data['personal_email'],
            'status' => 1,
            'employment_status_id' => $data['employment_status'],
            'department_id' => $data['department_id'],
            'designation' => strtolower($data['designation']),
            'type' => $data['type'],
            'cnic' => $data['cnic'],
            'date_of_birth' => $data['date_of_birth'],
            'location_id' => $data['location_id'],
            'current_address' => $data['current_address'],
            'permanent_address' => $data['permanent_address'],
            'city' => $data['city'],
            'joining_date' => $data['joining_date'],
            'manager_id' => $data['manager_id'],
        ];

        if (!empty($data->location_id)) {
            $arr['location_id'] = $data['location_id'];
        }

        if ($data['picture'] != '') {
            $picture = time() . '_' . $data['picture']->getClientOriginalName();
            $data->picture->move('storage/employees/profile/', $picture);
            $arr['picture'] = 'storage/employees/profile/' . $picture;
        }

        $employee = Employee::create($requestedData);
        $this->AssignRoleToNewEmployee($data, $employee);
        $params = [
            'emailAddress' => $data['official_email'],
            'primaryEmailAddress' => $data['official_email'],
            'displayName' => $data['firstname'] . ' ' . $data['lastname'],
            'password' => $password,
            'userExist' => false,
            'country' => 'pk',
        ];
        $zoho = new ZohoService();
        $asana = new AsanaService();
        $slack = new SlackService();
        if ($data['zoho']) {
            $response = $zoho->createZohoAccount($params);

            if ($response->original) {
                $asana->addUserToTeam($data->teams, $data->official_email);

                $employee->zuid = $response->original->data->zuid;
                $employee->account_id = $response->original->data->accountId;
                $employee->save();

                if ($employee) {
                    Mail::to($data->email)->later(
                        $when,
                        new ZohoInvitationMail($data->input(), $params['password'], $locale)
                    );
                }
            }
        }

        //check if slack is checked for invitation
        if ($data->slack) {
            //call the  slack trait method in app/Traits folder
            $slack->createSlackInvitation($data->official_email, $token);
            //slack mail
            Mail::to($data->official_email)->later($when, new SlackInvitationMail($data->input(), $locale));
        }
        $employee_id = $employee->id;
        $leave_types = LeaveType::get();
        $arr = [];
        foreach ($leave_types as $leave_type) {
            $arr[$leave_type->id] = ['count' => $leave_type->amount];
        }
        $employee->leaveTypes()->sync($arr);

        return $employee;
    }

    public function AssignRoleToNewEmployee($data, $employee)
    {
        $role = Role::where('id', $data->employeerole)->first();
        $employee->assignRole($role->name);
    }

    /**
     * @param $id
     * @return array
     */
    public function disableFieldWhenApprovalRequested($id)
    {
        if (!empty(ApprovalRequestedDataField::where('approval_id', 1)->where(
            'requested_for_id',
            $id
        )->whereNull('is_approved')->get())) {
            $employeeApprovals = ApprovalRequestedDataField::where('approval_id', 1)->where(
                'requested_for_id',
                $id
            )->whereNull('is_approved')->get();
            $disabledFields = [];
            foreach ($employeeApprovals as $employeeApproval) {
                $requestedData = json_decode($employeeApproval->requested_data, true);
                $modelName = array_keys($requestedData);
                if (in_array('employee', $modelName)) {
                    $fieldsList = array_keys($requestedData['employee']);
                    foreach ($fieldsList as $fieldList) {
                        $disabledFields[] = $fieldList;
                    }
                } elseif (isset($requestedData['education'])) {
                    $fieldsList = array_keys($requestedData['education']);
                    foreach ($fieldsList as $fieldList) {
                        $disabledFields[] = $fieldList;
                    }
                }
            }
        } else {
            $disabledFields = [];
        }
        return $disabledFields;
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function editEmployeeInformation($id)
    {
        $employee = Employee::with('bankAccount')->find($id);
        if (!$employee) {
            abort(404);
        }
        $education = Education::with('EducationType', 'SecondaryLanguage')->first();
        $currentUser = Auth::id();
        if (Auth::id() == $id) {
            $disableFields = $this->disableFieldWhenApprovalRequested($id);
        } else {
            $disableFields = [];
        }
        $employeeApproval = [];
        $data = [
            'employee' => $employee,
            'disableFields' => $disableFields,
        ];
        return $data;
    }

    /**
     * Update Employee Of Specific ID.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function updateEmployee($locale, $data, $id)
    {
        array_shift($data);
        $requestedData = $data;
        $roles = $this->getRoles(Auth::user());
        $employeeRoles = $this->getAllEmployeeRoles($roles);
        $currentUser = Auth::user();
        $changedFields = [];
        if (!$currentUser->isAdmin()) {
            if ($currentUser->id == $id) {
                $editWithApprovalFields = $this->getPermissionsOnRequestedFields('employee', $data, $employeeRoles[0]);
                $changedFields = $this->isEmployeeRequestedDataChanged($editWithApprovalFields, $id);
                if (count($changedFields) > 0) {
                    $changedFieldsArray = (new StoreApprovalForEmployeeRole())->execute(
                        $id,
                        'employee',
                        $changedFields
                    );
                    (new SendNotificationForInformationUpdate())->execute(
                        'employee',
                        $changedFields,
                        $changedFieldsArray['requestedDataID']
                    );
                }
            }
        }
        $adminPassword = Auth::user()->password;
        $employee = Employee::find($id);
        $employeePersonalDetails = $employee->getFillable();
        $data = (new CompareRequestedFieldWithFillable())->execute($data, $employeePersonalDetails);
        $data = array_diff_assoc($data, $changedFields);
        foreach ($data as $field => $value) {
            if (!empty($data['password']) && $field == 'password' && $data['password'] != null) {
                $employee->password = Hash::make($data['password']);
                continue;
            }
            $employee->{$field} = $value;

            // $employee->contact_no = $data['contact_no'];
            if (isset($data['picture']) && 'picture' == $field) {
                $picture = time() . '_' . $data['picture']->getClientOriginalName();
                $picture = time() . '_' . $data['picture']->getClientOriginalName();
                $data['picture']->move('storage/employees/profile/', $picture);
                $employee->picture = 'storage/employees/profile/' . $picture;
            }
            /*FIX ME
            // $employee->joining_date = $data['joining_date'];
            // $employee->exit_date = $data['exit_date'];
            // $employee->emergency_contact = $data['emergency_contact'];
            // $employee->emergency_contact_relationship = $data['emergency_contact_relationship'];
            // $employee->official_email = $data['official_email'];
            // $employee->personal_email = $data['personal_email'];
            // $employee->designation = $data['designation'];
            // $employee->employment_status = $data['employment_status'];
            // $employee->type = $data['type'];
            // $employee->location_id = $data['location_id'];
            // $employee->cnic = $data['cnic'];
            // $employee->date_of_birth = $data['date_of_birth'];
            // $employee->current_address = $data['current_address'];
            // $employee->permanent_address = $data['permanent_address'];
            // $employee->city = $data['city'];
            // $employee->department_id = $data['department_id'];
            // $employee->gender = $data['gender'];
            // $employee->joining_date = $data['joining_date'];
            // $employee->status = $data['status'];
             */
            if (!empty($data['password']) && 'password' == $field && null != $data['password']) {
                $employee->password = Hash::make($data['password']);
            }
            if ('employment_status' == $field && (strtolower('Terminated') == $data['employment_status'] || strtolower('Resigned') == $data['employment_status'])) {
                $employee->status = 0;
            }

            //admin password get from model confirmation box.
            $params = [
                'mode' => '',
                'zuid' => $employee->zuid,
                'password' => $adminPassword,
            ];
            //Fix Me
            //        if ($data['employee_status'] === '1') {
            //
            //            $params['mode'] = 'enableUser';
            //            $employee->status = 1;
            //            $this->updateZohoAccount($params, $employee->account_id);
            //        } elseif ($data['employee_status'] === '0') {
            //            $params['mode'] = 'disableUser';
            //            $employee->status = 0;
            //            $this->updateZohoAccount($params, $employee->account_id);
            //        }

            $when = Carbon::now()->addMinutes(10);
            //Fix Me
            //        if ($request->zoho) {
            //            $response = $this->updateZohoAccount($params);
            //
            //            if ($response->original) {
            //                if ($employee) {
            //                    Mail::to($request->email)->later(
            //                        $when,
            //                        new ZohoInvitationMail($request->input(), $params['password'])
            //                    );
            //                }
            //            }
            //        }

            try {
                Mail::to($data['official_email'])->later(
                    $when,
                    new UpdateAccount($employee->id, $data['password'], $locale)
                );
                Mail::to($data['personal_email'])->later(
                    $when,
                    new UpdateAccount($employee->id, $data['password'], $locale)
                );
            } catch (Exception $e) {
                if ('en' == $locale) {
                    Session::flash('error', 'Email Not Send Please Set Email Configuration In .env File');
                } elseif ('es' == $locale) {
                    Session::flash(
                        'error',
                        'El correo electrónico no se envía. Establezca la configuración de correo electrónico en el archivo .env.'
                    );
                }
            }

            //            if ($employee->roles->count() > 0) {
            //                $old_role = $employee->roles[0];
            //                $employee->removeRole($old_role);
            //            }
            //
            //            if (!empty($data['role_id'])) {
            //                $role = Role::find($data['role_id']);
            //                $employee->assignRole($role);
            //            }
            //        if ($request->permissions) {
            //            foreach ($request->permissions as $permission_id) {
            //                if (isset($request->permissions_checked)) {
            //                    if (in_array($permission_id, $request->permissions_checked)) {
            //                        $employee->givePermissionTo($permission_id);
            //                    } else {
            //                        $employee->revokePermissionTo($permission_id);
            //                    }
            //                }
            //            }
            //        }
        }

        $employee->save();

        //Comparing Bank Account Info
        $employeeBankInfo = new EmployeeBankAccount();
        $employeeBankDetails = $employeeBankInfo->getFillable();
        $employeeBankDetails = (new CompareRequestedFieldWithFillable())->execute(
            $requestedData,
            $employeeBankDetails
        );

        if (EmployeeBankAccount::where('employee_id', $id)->first() == null) {
            $bank = new EmployeeBankAccount();
            $bank->employee_id = $id;
            foreach ($employeeBankDetails as $bankField => $info) {
                $bank->{$bankField} = $info;
            }
            /*FixME
            // $bank->account_number = $data['account_number'];
            // $bank->iban = $data['iban_number'];
            // $bank->branch = $data['branch'];
            // $bank->bank_name = $data['bank_name'];
             */
            $bank->save();
        } else {
            $bank = EmployeeBankAccount::where('employee_id', $id)->first();

            foreach ($employeeBankDetails as $bankField => $info) {
                $bank->{$bankField} = $info;
            }
            /*FixME
            // $bank->account_number = $data['account_number'];
            // $bank->iban = $data['iban_number'];
            // $bank->branch = $data['branch'];
            // $bank->bank_name = $data['bank_name'];
             */
            $bank->save();
        }

        if (count($changedFields) > 0) {
            Session::flash('success', trans('language.Employee data requested succesfully'));
        } else {
            Session::flash('success', trans('language.Employee is updated succesfully'));
        }

        return $employee;
    }


    /**
     * @param $id
     * @param $data
     * @return array
     */
    public function updateEmployeeEducation($id, $data)
    {
        $roles = $this->getRoles(Auth::user());
        $employeeRoles = $this->getAllEmployeeRoles($roles);
        foreach ($employeeRoles as $role) {
            $editWithApprovalFields = $this->getPermissionsOnRequestedFields('education', $data, $role);
            $changedFields = $this->isEducationRequestedDataChanged($editWithApprovalFields, $id);
            if (count($changedFields) > 0) {
                $changedFieldsArray = (new StoreApprovalForEmployeeRole())->execute(
                    Auth::id(),
                    'education',
                    $changedFields,
                    $id
                );
                (new SendNotificationForInformationUpdate())->execute(
                    'education',
                    $changedFields,
                    $changedFieldsArray['requestedDataID'],
                    $id
                );
            }
        }
        return $changedFields;
    }

    /**
     * @param $type
     * @param $request
     * @param $role
     * @return array
     */
    public function getPermissionsOnRequestedFields($type, $request, $role)
    {
        $permissions = $role->permissions;
        $approvalFields = [];
        foreach ($permissions as $permission) {
            foreach ($request as $field => $value) {
                if ('edit_with_approval ' . $type . ' ' . $field == $permission->name) {
                    $approvalFields = array_add($approvalFields, $field, $value);
                }
            }
        }
        return $approvalFields;
    }

    public function isEducationRequestedDataChanged($fields, $id)
    {
        $education = Education::find($id)->toArray();
        $changedFields = [];
        foreach ($fields as $field => $value) {
            if ($education[$field] != $value) {
                $changedFields = array_add($changedFields, $field, $value);
            }
        }
        return $changedFields;
    }

    /**
     * @param $fields
     * @param $id
     * @return array
     */
    public function isEmployeeRequestedDataChanged($fields, $id)
    {
        $employee = Employee::find($id)->toArray();
        $changedFields = [];
        foreach ($fields as $field => $value) {
            if ($employee[$field] !== $value) {
                $changedFields = array_add($changedFields, $field, $value);
            }
        }
        return $changedFields;
    }

    /**
     * @param $roles
     * @return array
     */
    public function getAllEmployeeRoles($roles)
    {
        $employeeRoles = [];
        foreach ($roles as $role) {
            if ($role->type == 'employee') {
                $employeeRoles[] = $role;
            }
        }
        return $employeeRoles;
    }

    /*
     *Updating the Employee data if user has tried to update the Fields that required Approval
     *
     *
     */

    public function employeeRequiredFieldUpdate($id, $data)
    {
        $employee = Employee::where('id', $id)
            ->update($data);
    }

    //Employee Assets

    /**
     * @param $id
     *
     * @return array
     */
    public function employeeAssets($id)
    {
        $assets = Asset::where('employee_id', $id)->get();
        $asset_types = AssetsType::where('status', '1')->get();
        $data = [
            'assets' => $assets,
            'asset_types' => $asset_types,
        ];

        return $data;
    }

    /**
     * @param $data
     *
     * @return array
     */
    public function createEmployeeAsset($data)
    {
        $employeeId = $data['employee_id'];
        $assets = new Asset();
        $assets->asset_category = $data['asset_category'];
        $assets->asset_description = $data['asset_description'];
        $assets->serial = $data['serial'];
        $assets->assign_date = $data['assign_date'];
        $assets->return_date = $data['return_date'];
        $assets->employee_id = $employeeId;
        $assets->save();
        $data = [];
        $assets = Asset::where('employee_id', $employeeId)->get();
        $data = [
            'employeeId' => $employeeId,
            'assets' => $assets,
        ];

        return $data;
    }

    /**
     * @param $data
     * @param $id
     *
     * @return array
     */
    public function updateEmployeeAsset($data, $id)
    {
        $employee_id = $data['employee_id'];
        $assets = Asset::find($id);
        $assets->asset_category = $data['asset_category'];
        $assets->asset_description = $data['asset_description'];
        $assets->serial = $data['serial'];
        $assets->assign_date = $data['assign_date'];
        $assets->return_date = $data['return_date'];
        $assets->employee_id = $employee_id;
        $assets->save();
        $assets = Asset::where('employee_id', $employee_id)->get();
        $data = [
            'employeeId' => $employee_id,
            'assets' => $assets,
        ];

        return $data;
    }

    /**
     * @param $id
     * @param $data
     *
     * @return array
     */
    public function deleteEmployeeAsset($id, $data)
    {
        $employee_id = $data['employee_id'];
        $assets = Asset::find($id);
        $assets->delete();
        $assets = Asset::where('employee_id', $employee_id)->get();
        $data = [
            'assets' => $assets,
            'employeeId' => $employee_id,
        ];

        return $data;
    }

    /**
     * @param $data
     *
     * @return RedirectResponse
     */
    public function createNotes($data)
    {
        $note = new Note();
        $employeeId = $data['employee_id'];
        $note->username = Auth::user()->firstname;
        $note->note = $data['note'];
        $note->employee_id = $data['employee_id'];
        $note->save();
        $notes = Note::where('employee_id', $data['employee_id'])->get();
        $data = [
            'notes' => $notes,
            'employeeId' => $employeeId,
        ];

        return $data;
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function updateNotes($data, $id)
    {
        $note = Note::find($id);
        $note->username = Auth::user()->firstname;
        $employeeId = $data['employee_id'];
        $note->note = $data['note'];
        $note->employee_id = $data['employee_id'];
        $note->save();
        $notes = Note::where('employee_id', $data['employee_id'])->get();
        $data = [
            'employeeId' => $employeeId,
            'notes' => $notes,
        ];

        return $data;
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function deleteNotes($data, $id)
    {
        $note = Note::find($id);
        $note->delete();
        $employeeId = $data['employee_id'];
        $notes = Note::where('employee_id', $employeeId)->get();
        $data = [
            'employeeId' => $employeeId,
            'notes' => $notes,
        ];

        return $data;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function createEmployeeDocument($data)
    {
        $doc_exist = DocumentType::where('doc_type_name', $data['name'])->first();
        if (null == $doc_exist) {
            DocumentType::create([
                'doc_type_name' => $data['name'],
                'status' => $data['status'],
            ]);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $data
     * @param $id
     *
     * @return bool
     */
    public function updateEmployeeDocument($data, $id)
    {
        $doc_type = DocumentType::find($id);
        $doc_type->doc_type_name = $data['name'];
        $doc_type->status = $data['status'];
        $doc_type->save();

        return true;
    }

    /**
     * Delete Document Type.
     *
     * @return RedirectResponse
     */
    public function deleteEmployeeDocument($lang, $id, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $doc_type = DocumentType::find($id);
        $doc_type->delete();
        Session::flash('success', trans('language.Document type deleted successfully.'));

        return redirect($locale . '/doc-type')->with('locale', $locale);
    }

    public function getEmployee($id)
    {
        return Employee::where('id', $id)->first();
    }

    public function getEmployees()
    {
        return Employee::all();
    }

    public function getEmployeeTypeRoles()
    {
        $employeeRoles = Role::where('type', 'employee')->orderBy('id', 'asc')->get();
        $employeeRoles->shift(); //Leave first role of employee type, it is default role and not assignable to any user
        $employeeRoles->all();
        return $employeeRoles;
    }

    public function LastLogin()
    {
        $time = Employee::find(Auth::user()->id);
        $time->last_login = Carbon::now()->toDateTimeString();
        $time->save();
    }

    /**
     *check if current user can access basic information page of given employees (passed as parameter).
     */
    public function permissionsToAccessEmployeeInformation($data)
    {
        $basicInformation = null;
        foreach ($data as $model => $employeePermissions) {
            foreach ($employeePermissions as $employee => $permissions) {
                if ($model == 'employee' || $model == 'timeofftype') {
                    if (array_search('edit', $permissions) !== false || array_search(
                        'edit_with_approval',
                        $permissions
                    ) !== false || array_search('view', $permissions) !== false) {
                        $basicInformation[$model][$employee] = true;
                    } else {
                        $basicInformation[$model][$employee] = false;
                    }
                }
            }
        }

        return $basicInformation;
    }

    public function getAuthorizedUserPermissions($employees)
    {
        $permissions = [];
        $permissions['employee'] = $this->getAccessibleFieldList(Employee::class, $employees);
        $permissions['department'] = $this->getAccessibleFieldList(Department::class, $employees);
        $permissions['location'] = $this->getAccessibleFieldList(Location::class, $employees);
        $permissions['educationType'] = $this->getAccessibleFieldList(EducationType::class, $employees);
        $permissions['education'] = $this->getAccessibleFieldList(Education::class, $employees);
        $permissions['secondaryLanguage'] = $this->getAccessibleFieldList(SecondaryLanguage::class, $employees);
        $permissions['assets'] = $this->getAccessibleFieldList(Asset::class, $employees);
        $permissions['visaType'] = $this->getAccessibleFieldList(VisaType::class, $employees);
        $permissions['employeeVisa'] = $this->getAccessibleFieldList(EmployeeVisa::class, $employees);
        $permissions['employeeAccessLevel'] = $this->getAccessibleFieldList(Role::class, $employees);
        $permissions['employeeDocument'] = $this->getAccessibleFieldList(EmployeeDocument::class, $employees);
        $permissions['benefits'] = $this->getAccessibleFieldList(EmployeeBenefit::class, $employees);
        $permissions['benefitGroup'] = $this->getAccessibleFieldList(BenefitGroup::class, $employees);
        $permissions['dependents'] = $this->getAccessibleFieldList(EmployeeDependent::class, $employees);
        $permissions['benefitPlans'] = $this->getAccessibleFieldList(BenefitPlan::class, $employees);
        $permissions['timeofftype'] = $this->getAccessibleFieldList(TimeOffType::class, $employees);
        $permissions['policy'] = $this->getAccessibleFieldList(Policy::class, $employees);
        $permissions['tasks'] = $this->getAccessibleFieldList(EmployeeTask::class, $employees);

        return $permissions;
    }

    public function getEmployeeRoles($employee)
    {
        //A user with employee type role can only have one role. Multiple employee type roles are not allowed. (except for the
        //sub-roles)
        $role = $employee->roles()->first();
        $roles = null;
        if (empty($role)) {
            $roles['noAccess'] = true;
            $roles['employeeRoles'] = $this->getEmployeeTypeRoles();
        } elseif ($role->type == 'employee') {
            $roles['currentRole'] = $role;
            $roles['employeeRoles'] = $this->getEmployeeTypeRoles();
        }

        return $roles;
    }

    /**
     * @param $policyMethod
     * @param $controllerName
     * @param $modelName
     * @param $employees
     *
     * @return mixed
     */
    public function authorizeUser($policyMethod, $controllerName, $modelName, $employees)
    {
        $models = [
            'Employee' => Employee::class,
            'employeeDocument' => EmployeeDocument::class,
            'assets' => Asset::class,
        ];

        return $controllerName->authorize($policyMethod, [$models[$modelName], $employees]);
    }

    public function getEmployeeInformation($id)
    {
        $data = Employee::where('id', $id)
            ->where('status', '!=', 0)->first();

        return $data;
    }

    public function getEmployeeFillable()
    {
        $employee = new Employee();
        return $employee->getFillable();
    }

    public function getEmployeeAccountFillable()
    {
        $employee = new EmployeeBankAccount();
        return $employee->getFillable();
    }
}
