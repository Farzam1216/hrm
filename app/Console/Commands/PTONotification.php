<?php

namespace App\Console\Commands;

use App\Domain\Employee\Models\Employee;
use App\Domain\TimeOff\Models\RequestTimeOff;
use App\Domain\TimeOff\Models\RequestTimeOffNotification;
use App\Domain\TimeOff\Actions\StoreTodayApprovedTimeOffNotifications;
use App\Domain\Integrations\Actions\SendTodayApprovedTimeOffNotificationOnPumble;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class PTOTransaction
 *
 * @package App\Console\Commands
 */
class PTONotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pto:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Cron Job to notify hr/admin/manager about today's approved time off of direct/indirect employees through system notification and pumble channel";

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
        \Log::info("pto:notify is working fine!");

        $today = Carbon::now()->format("Y-m-d");
        $approved_time_offs = RequestTimeOff::where('status', 'approved')->with(['requestTimeOffNotification', 'assignTimeOff', 'assignTimeOff.timeOffType'])->get();

        foreach ($approved_time_offs as $approved_time_off) {
            $dates = [];
            $dateTo = Carbon::parse($approved_time_off->to)->format('Y-m-d');
            $dateFrom = Carbon::parse($approved_time_off->from)->format('Y-m-d');
            $periods = \Carbon\CarbonPeriod::create($dateTo, $dateFrom);
            foreach ($periods as $key => $period) {
                $dates[$key+1] = $period->format('Y-m-d');
            }
            foreach ($dates as $date) {
                if ($date == $today) {
                    if (!$approved_time_off->requestTimeOffNotification) {
                        $employee = Employee::where('id', $approved_time_off->employee_id)->first();

                        (new StoreTodayApprovedTimeOffNotifications())->execute($employee->id);

                        if ($approved_time_off->note == 'None') {
                            (new SendTodayApprovedTimeOffNotificationOnPumble)->execute('@' . $employee->firstname.$employee->employee_no . ' is on approved time off for today of "' . $approved_time_off->assignTimeOff->timeOffType->time_off_type_name . '". Approved time off is from ' . Carbon::parse($approved_time_off->to)->format('d-m-Y') . ' to ' . Carbon::parse($approved_time_off->from)->format('d-m-Y'));
                        }

                        if ($approved_time_off->note != 'None') {
                            (new SendTodayApprovedTimeOffNotificationOnPumble)->execute('@' . $employee->firstname.$employee->employee_no . ' is on approved time off for today due to "' . $approved_time_off->note . '". Approved time off is from ' . Carbon::parse($approved_time_off->to)->format('d-m-Y') . ' to ' . Carbon::parse($approved_time_off->from)->format('d-m-Y'));
                        }

                        $notification = new RequestTimeOffNotification();
                        $notification->request_time_off_id = $approved_time_off->id;
                        $notification->status = 1;
                        $notification->save();
                    }
                }
            }
        }

        $this->info('pto:notify Command Run successfully!');
    }
}
