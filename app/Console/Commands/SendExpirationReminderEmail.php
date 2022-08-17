<?php

namespace App\Console\Commands;

use App\Domain\Benefit\Models\BenefitPlan;
use App\Domain\Employee\Models\Employee;
use App\Mail\PlanExpirationReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendExpirationReminderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:plan-expiration-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to admin 30 days and 10 days prior to the plan end date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $plans = $this->getPlansWithEndDate();
        $expiringSoonPlans = null;
        if (isset($plans)) {
            $count = 0;
            foreach ($plans as $plan) {
                if ($this->isExpiringSoon($plan->end_date)) {
                    $expiringSoonPlans[$count] = $plan;
                    $endDate = $plan->end_date;
                    $count++;
                }
            }
            $this->sendEmailToAllAdmins($expiringSoonPlans, $endDate);
        }
    }

    /**
     * @return mixed
     */
    public function getAdmins()
    {
        //TODO: for now, all employees have admin rights and admin has a designation "admin",
        //  though, later (after setting roles) we will need to change this code.
        return Employee::where('designation', 'admin')->get();
    }

    /**
     * @param $expiringSoonPlans
     * @param $endDate
     */
    public function sendEmailToAllAdmins($expiringSoonPlans, $endDate)
    {
        if (isset($expiringSoonPlans)) {
            //TODO: The Account Owner and Full Admin users, as well as any custom user that has been granted access under settings to manage benefits will receive the email.
            $admins = $this->getAdmins();
            if (isset($admins)) {
                foreach ($admins as $admin) {
                    if (isset($admin->official_email)) {
                        try {
                            $benefitsPageURL = '/en/benefit-plan';
                            Mail::to($admin->official_email)->send(new PlanExpirationReminder($admin, $expiringSoonPlans, $benefitsPageURL, Carbon::parse($endDate)->format('F d, Y')));
                        } catch (\Exception $e) {
                            error_log("Plan Expiration Email not sent, because following error occured: $e");
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $planEndDate
     * @return bool
     */
    public function isExpiringSoon($planEndDate)
    {
        $currentDate = Carbon::now()->startOfDay();
        $expiryDate = Carbon::parse($planEndDate)->startOfDay();
        if ($expiryDate->gt($currentDate)) {
            $remainingDays = ($expiryDate->diffInDays($currentDate));
            if (($remainingDays == 10 or $remainingDays == 30)) {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getPlansWithEndDate()
    {
        $plans = BenefitPlan::where('end_date', '<>', '')->orWhereNotNull('end_date')->get();
        return $plans;
    }
}
