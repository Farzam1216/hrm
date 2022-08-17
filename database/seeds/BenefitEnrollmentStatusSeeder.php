<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BenefitEnrollmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $benefitStatusDetails=[
            ['id'              =>1 , 'status'=>'notEligible' , 'future_message'=>'will be ineligible for $plan on $date' , 'current_message'=>'Ineligible to use $plan on $date' ,
             'status_edit_form'=>'Edit Not Eligible' , 'status_lists'=>json_encode([
                0=>['value'=>'notEligible' , 'label'=>'Edit Not Eligible'] ,
                1=>['value'=>'eligible' , 'label'=>'Mark as eligible'] ,
                2=>['value'=>'enroll' , 'label'=>'Enroll'] ,
                3=>['value'=>'waive' , 'label'=>'Waive']
            ])] ,
            ['id'              =>2 , 'status'=>'eligible' , 'future_message'=>'will be eligible for $plan on $date' , 'current_message'=>'Eligible for $plan on $date' ,
             'status_edit_form'=>'Edit Eligibility' , 'status_lists'=>json_encode([
                0=>['value'=>'eligible' , 'label'=>'Edit Eligibility'] ,
                1=>['value'=>'enroll' , 'label'=>'Enroll'] ,
                2=>['value'=>'waive' , 'label'=>'Waive'] ,
                3=>['value'=>'notEligible' , 'label'=>'Mark as Not Eligible']
            ])] ,
            ['id'              =>3 , 'status'=>'enroll' , 'future_message'=>'Will be enrolled in $plan on $date' , 'current_message'=>'Enrolled in $plan on $date' ,
             'status_edit_form'=>'Edit Enrollment' , 'status_lists'=>json_encode([
                0=>['value'=>'enroll' , 'label'=>'Edit Enrollment'] ,
                1=>['value'=>'withdraw' , 'label'=>'Withdraw'] ,
                2=>['value'=>'terminateCoverage' , 'label'=>'Terminate Coverage']
            ])] ,
            ['id'              =>4 , 'status'=>'waive' , 'future_message'=>'$plan will be waived on $date' , 'current_message'=>'Waived $plan on $date' ,
             'status_edit_form'=>'Edit Waive' , 'status_lists'=>json_encode([
                0=>['value'=>'waive' , 'label'=>'Edit Waive'] ,
                1=>['value'=>'enroll' , 'label'=>'Enroll'] ,
                2=>['value'=>'notEligible' , 'label'=>'Mark as Not Eligible']
            ])] ,
            ['id'              =>5 , 'status'=>'withdraw' , 'future_message'=>'will be withdrawn from $plan on $date' , 'current_message'=>'Withdrawn from $plan on $date' ,
             'status_edit_form'=>'Edit Withdraw' , 'status_lists'=>json_encode([
                0=>['value'=>'withdraw' , 'label'=>'Edit Withdraw'] ,
                1=>['value'=>'enroll' , 'label'=>'Enroll'] ,
                2=>['value'=>'waive' , 'label'=>'Waive'] ,
                3=>['value'=>'notEligible' , 'label'=>'Mark as Not Eligible']
            ])] ,
            ['id'              =>6 , 'status'=>'terminateCoverage' , 'future_message'=>'$plan coverage will be terminated on $date' , 'current_message'=>'Terminated coverage for $plan on $date' ,
             'status_edit_form'=>'Edit Terminate Coverage' , 'status_lists'=>json_encode([
                0=>['value'=>'terminateCoverage' , 'label'=>'Edit Terminate Coverage'] ,
                1=>['value'=>'enroll' , 'label'=>'Enroll'] ,
                2=>['value'=>'eligible' , 'label'=>'Mark as Eligible']
            ])]
        ];

        DB::table('benefit_status_details')->insert($benefitStatusDetails);
    }
}
