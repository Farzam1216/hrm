<?php

use App\Domain\ACL\Models\Role;
use App\Domain\Approval\Models\Approval;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Domain\Employee\Models\Employee;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'type' => 'custom',
        'description' => $faker->sentence()
    ];
});

// $factory->define(App\User::class, function (Faker $faker) {
//     return [
//         'name' => $faker->name,
//         'email' => $faker->unique()->safeEmail,
//         'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
//         'remember_token' => str_random(10),
//     ];
// });

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'contact_no' => $faker->phoneNumber,
        'official_email' => $faker->unique()->safeEmail,
        'personal_email' => $faker->safeEmail,
        'password' => bcrypt('secret'), // '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'
        'type' => 'Full Time',
        'status' => 1,
        'location_id' => 1,
        'zuid' => 123,
        'account_id' => 123,
    ];
});

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name'       => $faker->name,
        'guard_name' => 'web',
        'type'       => 'employee',
        'description' => 'Description',
        'sub_role'   => null,
    ];
});

$factory->define(Approval::class, function (Faker $faker) {
    return [
        'approval_type_id' => 3,
        'name' => $faker->name,
        'description' => 'This is test description',
        'status' => 0
    ];
});
$factory->define(EmployeeDependent::class, function (Faker $faker) {
    return [
        'employee_id' => factory(Employee::class)->create()->id,
        'first_name' => $faker->firstName,
        'middle_name' => null,
        'last_name' => $faker->lastName,
        'date_of_birth' => $faker->date(),
        'SSN' => null,
        'SIN' => null,
        'relationship' => null,
        'gender' => null,
        'street1' => $faker->streetAddress,
        'street2' => $faker->streetName,
        'city'   => $faker->city,
        'state'  => $faker->state,
        'zip'    => $faker->countryCode,
        'country' => $faker->country,
        'is_us_citizen' => 0,
        'is_student' => 0
    ];
});
