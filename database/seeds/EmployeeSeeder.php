<?php


use App\Domain\ACL\Models\Role;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\Location;
use App\Traits\ZohoTrait;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    use ZohoTrait;


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $nin = '';
        $employee1 = Employee::create([
            'firstname' => $faker->name,
            'lastname' => '',
            'contact_no' => $faker->phoneNumber,
            'emergency_contact' => $faker->phoneNumber,
            'emergency_contact_relationship' => 'brother',
            'password' => bcrypt('admin123'),
            'nin' => '35215254685412',
            'date_of_birth' => $faker->date,
            'official_email' => 'admin@hr.com',
            'personal_email' => 'admin@gmail.com',
            'city' => $faker->city,
            'gender' => 'Male',
            'marital_status' => 'Single',
            'status' => 1,
            'joining_date' => date("Y-m-d"),
            'zuid' => '123',
            'account_id' => '123',
            'invite_to_zoho' => 0,
            'invite_to_slack' => 0,
            'invite_to_asana' => 0,
        ]);

        $role = Role::find(1);
        $employee1->assignRole($role);
        // for ($i = 0; $i < 5; $i++) {
        //     for ($i = 0; $i < 14; $i++) {
        //         $nin = $nin . '' . rand(0, 9);
        //     }
        //     $employee2 = Employee::create([
        //         'firstname' => $faker->firstName($gender = 'male'),
        //         'lastname' => $faker->lastName($gender = 'male'),
        //         'contact_no' => $faker->phoneNumber,
        //         'emergency_contact' => $faker->phoneNumber,
        //         'emergency_contact_relationship' => 'brother',
        //         'password' => bcrypt('123456'),
        //         'nin' => $nin,
        //         'date_of_birth' => $faker->date,
        //         'official_email' => 'emp1@example.com',
        //         'personal_email' => $faker->email,
        //         'designation_id' => 2,
        //         'city' => $faker->jobTitle,
        //         'location_id' => Location::pluck('id')->first(),
        //         'department_id'=>1,
        //         'work_schedule_id'=>1,
        //         'Gender' => 'Male',
        //         'marital_status' => 'Single',
        //         'status' => 1,
        //         'joining_date' => date("Y-m-d"),
        //         'zuid' => '123',
        //         'account_id' => '123',
        //         'invite_to_zoho' => 0,
        //         'invite_to_slack' => 0,
        //         'invite_to_asana' => 0,
        //     ]);
        //     //            $array=[26=>2,27=>2,29=>2,30=>2,31=>2,78=>2,79=>2,80=>2,148=>2,150=>2,151=>2,153=>2,157=>2,158=>2,160=>2,162=>2];
        //     //            $role2 = Role::find(2);
        //     //            $employee2->assignRole($role2);
        //     //            foreach ($array as $id => $arr) {
        //     //                $employee2->givePermissionTo($id);
        //     //            }
        //     $employee3 = Employee::create([
        //         'firstname' => $faker->firstName($gender = 'male'),
        //         'lastname' => $faker->lastName($gender = 'male'),
        //         'contact_no' => $faker->phoneNumber,
        //         'emergency_contact' => $faker->phoneNumber,
        //         'emergency_contact_relationship' => 'brother',
        //         'password' => bcrypt('123456'),
        //         'nin' => $nin,
        //         'date_of_birth' => $faker->date,
        //         'official_email' => 'emp2@example.com',
        //         'personal_email' => $faker->email,
        //         'designation_id' => 3,
        //         'city' => $faker->jobTitle,
        //         'location_id' => Location::pluck('id')->first(),
        //         'department_id'=>2,
        //         'work_schedule_id'=>1,
        //         'Gender' => 'Male',
        //         'marital_status' => 'Single',
        //         'status' => 1,
        //         'zuid' => '123',
        //         'account_id' => '123',
        //         'joining_date' => date("Y-m-d"),
        //         'invite_to_zoho' => 0,
        //         'invite_to_slack' => 0,
        //         'invite_to_asana' => 0,
        //     ]);
        //     $employee4 = Employee::create([
        //         'firstname' => $faker->firstName($gender = 'male'),
        //         'lastname' => $faker->lastName($gender = 'male'),
        //         'contact_no' => $faker->phoneNumber,
        //         'emergency_contact' => $faker->phoneNumber,
        //         'emergency_contact_relationship' => 'brother',
        //         'password' => bcrypt('123456'),
        //         'nin' => $nin,
        //         'date_of_birth' => $faker->date,
        //         'official_email' => 'emp3@example.com',
        //         'personal_email' => $faker->email,
        //         'designation_id' => 4,
        //         'city' => $faker->jobTitle,
        //         'location_id' => Location::pluck('id')->first(),
        //         'department_id'=>3,
        //         'work_schedule_id'=>1,
        //         'Gender' => 'Male',
        //         'marital_status' => 'Single',
        //         'status' => 1,
        //         'zuid' => '123',
        //         'account_id' => '123',
        //         'joining_date' => date("Y-m-d"),
        //         'invite_to_zoho' => 0,
        //         'invite_to_slack' => 0,
        //         'invite_to_asana' => 0,
        //     ]);
        // }

        // $hrManager = Employee::create([
        //     'firstname' => $faker->firstName($gender = 'male'),
        //     'lastname' => $faker->lastName($gender = 'male'),
        //     'contact_no' => $faker->phoneNumber,
        //     'emergency_contact' => $faker->phoneNumber,
        //     'emergency_contact_relationship' => 'brother',
        //     'password' => bcrypt('hrmanager123'),
        //     'nin' => '35215254685112',
        //     'date_of_birth' => $faker->date,
        //     'official_email' => 'hrmanager@hr.com',
        //     'personal_email' => $faker->email,
        //     'designation_id' => 2,
        //     'city' => $faker->jobTitle,
        //     'location_id' => Location::pluck('id')->first(),
        //     'department_id'=>1,
        //     'Gender' => 'Male',
        //     'marital_status' => 'Single',
        //     'status' => 1,
        //     'joining_date' => date("Y-m-d"),
        //     'zuid' => '123',
        //     'account_id' => '123',
        //     'invite_to_zoho' => 0,
        //     'invite_to_slack' => 0,
        //     'invite_to_asana' => 0,
        // ]);

        // $hrManager = Employee::create([
        //     'firstname' => $faker->firstName($gender = 'male'),
        //     'lastname' => $faker->lastName($gender = 'male'),
        //     'contact_no' => $faker->phoneNumber,
        //     'emergency_contact' => $faker->phoneNumber,
        //     'emergency_contact_relationship' => 'brother',
        //     'password' => bcrypt('manager123'),
        //     'nin' => '35215254685112',
        //     'date_of_birth' => $faker->date,
        //     'official_email' => 'manager@hr.com',
        //     'personal_email' => $faker->email,
        //     'designation_id' => 2,
        //     'city' => $faker->jobTitle,
        //     'location_id' => Location::pluck('id')->first(),
        //     'department_id'=>1,
        //     'Gender' => 'Male',
        //     'marital_status' => 'Single',
        //     'status' => 1,
        //     'joining_date' => date("Y-m-d"),
        //     'zuid' => '123',
        //     'account_id' => '123',
        //     'invite_to_zoho' => 0,
        //     'invite_to_slack' => 0,
        //     'invite_to_asana' => 0,
        // ]);
    }
}
