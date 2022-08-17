<?php

use Illuminate\Database\Seeder;

class ApprovalFormFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('approval_form_fields')->truncate();
        $personals = [
            [
                'group' => 'Personal',
                'name' => 'Employee No',
                'model' => 'Employee',
                'type' => 'text',
                'content' => null,
                'field_name' => 'id',
            ],

            [
                'group' => 'Personal',
                'name' => 'Status',
                'model' => 'Employee',
                'type' => 'list',
                'content' => json_encode(['fixed' => '1', 'options' => [0 => 'Inactive', 1 => 'Active']]),
                'field_name' => 'status',
            ],

            [
                'group' => 'Personal',
                'name' => 'First Name',
                'model' => 'Employee',
                'type' => 'text',
                'content' => null,
                'field_name' => 'firstname',
            ],

            [
                'group' => 'Personal',
                'name' => 'Last Name',
                'model' => 'Employee',
                'type' => 'text',
                'content' => null,
                'field_name' => 'lastname',
            ],

            [
                'group' => 'Personal',
                'name' => 'Birth Date',
                'model' => 'Employee',
                'type' => 'date',
                'content' => null,
                'field_name' => 'date_of_birth',
            ],

            [
                'group' => 'Personal',
                'name' => 'NIN',
                'model' => 'Employee',
                'type' => 'text',
                'content' => null,
                'field_name' => 'nin',
            ],

            [
                'group' => 'Personal',
                'name' => 'Gender',
                'model' => 'Employee',
                'type' => 'list',
                'content' => json_encode(['fixed' => '1', 'options' => ['male' => 'Male', 'female' => 'Female']]),
                'field_name' => 'gender',
            ],

            [
                'group' => 'Personal',
                'name' => 'Current Address',
                'model' => 'Employee',
                'type' => 'text',
                'content' => null,
                'field_name' => 'current_address',
            ],

            [
                'group' => 'Personal',
                'name' => 'Permanent Address',
                'model' => 'Employee',
                'type' => 'text',
                'content' => null,
                'field_name' => 'permanent_address',
            ],

            [
                'group' => 'Personal',
                'name' => 'City',
                'model' => 'Employee',
                'type' => 'text',
                'content' => null,
                'field_name' => 'city',
            ],

            [
                'group' => 'Personal',
                'name' => 'Contact',
                'model' => 'Employee',
                'type' => 'tel',
                'content' => null,
                'field_name' => 'contact_no',
            ],

            [
                'group' => 'Personal',
                'name' => 'Official Email',
                'model' => 'Employee',
                'type' => 'email',
                'content' => null,
                'field_name' => 'official_email',
            ],

            [
                'group' => 'Personal',
                'name' => 'Personal Email',
                'model' => 'Employee',
                'type' => 'email',
                'content' => null,
                'field_name' => 'personal_email',
            ],

            [
                'group' => 'Personal',
                'name' => 'Secondary Language',
                'model' => 'Education',
                'type' => 'list',
                'content' => json_encode(['fixed' => '0', 'options' => 'language_name in SecondaryLanguage']),
                'field_name' => 'secondary_language_id',
            ],
        ];
        $education = [
            [
                'group' => 'Education',
                'name' => 'Institute Name',
                'model' => 'Education',
                'type' => 'text',
                'content' => null,
                'field_name' => 'institute_name',
            ],
            [
                'group' => 'Education',
                'name' => 'Major',
                'model' => 'Education',
                'type' => 'text',
                'content' => null,
                'field_name' => 'major',
            ],
            [
                'group' => 'Education',
                'name' => 'CGPA',
                'model' => 'Education',
                'type' => 'text',
                'content' => null,
                'field_name' => 'cgpa',
            ],
            [
                'group' => 'Education',
                'name' => 'Date Start',
                'model' => 'Education',
                'type' => 'date',
                'content' => null,
                'field_name' => 'date_start',
            ],
            [
                'group' => 'Education',
                'name' => 'Date End',
                'model' => 'Education',
                'type' => 'date',
                'content' => null,
                'field_name' => 'date_end',
            ],
        ];
        $visa = [
            [
                'group' => 'Visa',
                'name' => 'Visa Name',
                'model' => 'EmployeeVisa',
                'type' => 'list',
                'content' => json_encode(['fixed' => '0', 'options' => 'visa_type in VisaType']),
                'field_name' => 'visa_type_id',
            ],
            [
                'group' => 'Visa',
                'name' => 'Issuing Country',
                'model' => 'EmployeeVisa',
                'type' => 'list',
                'content' => json_encode(['fixed' => '0', 'options' => 'name in Country']),
                'field_name' => 'country_id',
            ],
            [
                'group' => 'Visa',
                'name' => 'Visa Note',
                'model' => 'EmployeeVisa',
                'type' => 'text',
                'content' => null,
                'field_name' => 'note',
            ],
            [
                'group' => 'Visa',
                'name' => 'Visa Date Start',
                'model' => 'EmployeeVisa',
                'type' => 'date',
                'content' => null,
                'field_name' => 'issue_date',
            ],
            [
                'group' => 'Visa',
                'name' => 'Visa Date End',
                'model' => 'EmployeeVisa',
                'type' => 'date',
                'content' => null,
                'field_name' => 'expire_date',
            ],
        ];
        $job = [
            [
                'group' => 'Job',
                'name' => 'Hire Date',
                'model' => 'Employee',
                'type' => 'date',
                'content' => null,
                'field_name' => 'joining_date',
            ],
            [
                'group' => 'Job',
                'name' => 'Exit Date',
                'model' => 'Employee',
                'type' => 'date',
                'content' => null,
                'field_name' => 'exit_date',
            ],
            [
                'group' => 'Job',
                'name' => 'Location',
                'model' => 'Employee',
                'type' => 'list',
                'content' => json_encode(['fixed' => '0', 'options' => 'name in Location']),
                'field_name' => 'location_id',
            ],
        ];
        $notes = [
            [
                'group' => 'Notes',
                'name' => 'Notes',
                'model' => 'Note',
                'type' => 'text',
                'content' => null,
                'field_name' => 'note',
            ],
        ];
        $benefits = [
            [
                'group' => 'Benefits',
                'name' => 'Dependent First Name',
                'model' => 'EmployeeDependent',
                'type' => 'text',
                'content' => null,
                'field_name' => 'first_name',
            ],
            [
                'group' => 'Benefits',
                'name' => 'Dependent Middle Name',
                'model' => 'EmployeeDependent',
                'type' => 'text',
                'content' => null,
                'field_name' => 'middle_name',
            ],
            [
                'group' => 'Benefits',
                'name' => 'Dependent Last Name',
                'model' => 'EmployeeDependent',
                'type' => 'text',
                'content' => null,
                'field_name' => 'last_name',
            ],
            [
                'group' => 'Benefits',
                'name' => 'Dependent Birth Date',
                'model' => 'EmployeeDependent',
                'type' => 'date',
                'content' => null,
                'field_name' => 'date_of_birth',
            ],
            [
                'group' => 'Benefits',
                'name' => 'Dependent SSN',
                'model' => 'EmployeeDependent',
                'type' => 'text',
                'content' => null,
                'field_name' => 'SSN',
            ],
            [
                'group' => 'Benefits',
                'name' => 'Dependent SIN',
                'model' => 'EmployeeDependent',
                'type' => 'text',
                'content' => null,
                'field_name' => 'SIN',
            ],
            [
                'group' => 'Benefits',
                'name' => 'Dependent Gender',
                'model' => 'EmployeeDependent',
                'type' => 'list',
                'content' => json_encode(['fixed' => '1', 'options' => ['male' => 'Male', 'female' => 'Female']]),
                'field_name' => 'gender',
            ],
            [
                'group' => 'Benefits',
                'name' => 'Dependent Street 1',
                'model' => 'EmployeeDependent',
                'type' => 'text',
                'content' => null,
                'field_name' => 'street1'
            ],
            [
                'group' => 'Benefits',
                'name' => 'Dependent Relationship',
                'model' => 'EmployeeDependent',
                'type' => 'text',
                'content' => null,
                'field_name' => 'relationship',
            ],
            [
                'group' => 'Benefits',
                'name' => 'Dependent Is US Citizen',
                'model' => 'EmployeeDependent',
                'type' => 'checkbox',
                'content' => null,
                'field_name' => 'is_us_citizen',
            ],
            [
                'group' => 'Benefits',
                'name' => 'Dependent Is Student',
                'model' => 'EmployeeDependent',
                'type' => 'checkbox',
                'content' => null,
                'field_name' => 'is_student',
            ],
            [
                'group' => 'Benefits',
                'name' => 'Dependent Home Phone',
                'model' => 'EmployeeDependent',
                'type' => 'text',
                'content' => null,
                'field_name' => 'home_phone',
            ],
        ];
        $assets = [
            [
                'group' => 'Assets',
                'name' => 'Asset Category',
                'model' => 'Asset',
                'type' => 'list',
                'content' => json_encode(['fixed' => '0', 'options' => 'name in AssetsType']),
                'field_name' => 'asset_category',
            ],
            [
                'group' => 'Assets',
                'name' => 'Asset Description',
                'model' => 'Asset',
                'type' => 'text',
                'content' => null,
                'field_name' => 'asset_description',
            ],
            [
                'group' => 'Assets',
                'name' => 'Serial',
                'model' => 'Asset',
                'type' => 'text',
                'content' => null,
                'field_name' => 'serial',
            ],
            [
                'group' => 'Assets',
                'name' => 'Date Loaned',
                'model' => 'Asset',
                'type' => 'date',
                'content' => null,
                'field_name' => 'assign_date',
            ],
            [
                'group' => 'Assets',
                'name' => 'Date Return',
                'model' => 'Asset',
                'type' => 'date',
                'content' => null,
                'field_name' => 'return_date',
            ],
        ];
        NOTE: FIXME: //timeoff format is not persistent
        // $timeOff = [
        //     [
        //         'group' => 'TimeOff',
        //         'name' => 'Accrual Level Name',
        //         'model' => 'Employee',
        //         'type' => 'date',
        //         'content' => null,
        //         'field_name' => 'time_off_type_name'
        //     ],
        // ];
        // 'TimeOff' => [
        //     'Accrual Level Name' => 'time_off_type_name', 'Accrual Level Start Date' => 'accrual_date',
        // ],

        $comments = [
            [
                'group' => 'Default',
                'name' => 'Comments',
                'model' => null,
                'type' => 'textarea',
                'content' => null,
                'field_name' => 'comments',
                'status' => 'static',
            ],
        ];
        DB::table('approval_form_fields')->insert($personals);
        DB::table('approval_form_fields')->insert($education);
        DB::table('approval_form_fields')->insert($visa);
        DB::table('approval_form_fields')->insert($job);
        DB::table('approval_form_fields')->insert($notes);
        DB::table('approval_form_fields')->insert($benefits);
        DB::table('approval_form_fields')->insert($assets);
        DB::table('approval_form_fields')->insert($comments);
    }
}
