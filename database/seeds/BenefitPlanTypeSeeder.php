<?php

use Illuminate\Database\Seeder;

class BenefitPlanTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $benefitPlanTypes=[
            ['name'=>'Health' , 'type_details'=>'["coverage_options", "cost_rate", "plan_end_date" , "end_date_required" ]','icon' => 'fas fa-heartbeat'] ,
            ['name'=>'Dental' , 'type_details'=>'["coverage_options", "cost_rate", "plan_end_date" , "end_date_required" ]','icon' => 'fas fa-tooth'] ,
            ['name'=>'Vision' , 'type_details'=>'["coverage_options", "cost_rate", "plan_end_date" , "end_date_required" ]','icon' => 'far fa-eye'] ,
            ['name'=>'Supplemental Health' , 'type_details'=>'["coverage_options", "cost_rate", "plan_end_date" , "end_date_required" ]','icon' => 'fas fa-heartbeat'] ,
            ['name'=>'Retirement' , 'type_details'=>'["plan_end_date"]','icon' => 'fas fa-piggy-bank'] ,
            ['name'=>'Reimbursement' , 'type_details'=>'["reimbursement_plan_amount"]','icon' => 'fas fa-money-check-alt'] ,
            ['name'=>'Health Savings Account' , 'type_details'=>'["plan_end_date"]','icon' => 'fas fa-hand-holding-usd'] ,
            ['name'=>'Flex Spending Account' , 'type_details'=>'["plan_end_date"]','icon' => 'fas fa-wallet'] ,
            ['name'=>'Life Insurance' , 'type_details'=>'["plan_end_date"]','icon' => 'fas fa-shield-alt'] ,
            ['name'=>'Disability' , 'type_details'=>'["plan_end_date"]','icon' => 'fas fa-wheelchair'] ,
            ['name'=>'Other' , 'type_details'=>'["plan_end_date"]' ,'icon' => null]];
        DB::table('benefit_plan_types')->insert($benefitPlanTypes);
    }
}
