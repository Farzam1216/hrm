<?php

namespace Tests\Unit;

use App\Domain\Employee\Models\Employee;
use App\Notifications\DefaultApprovalNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_send_email_and_notification()
    {
        Notification::fake();
        $admin = Employee::role('admin')->first();
        $user = factory(Employee::class)->create([
            'password' => bcrypt($password = '12345'),
        ]);
        //force fill employee model notify need email field we have official_email
        $admin = (new Employee)->forceFill([
            'id' => $admin['id'],
            'name' => $admin['firstname'],
            'email' => $admin['official_email'],
        ]);
        $testDetail = [
            'subject' => 'Information Update Request',
            'ApproveURL' => url('/approve'),
            'DisapproveURL' => url('/disapprove'),
            'requester' => 'Muhammad Ilyas',
            'approval_type' => 'Information Update Request',
            'body' => 'Muhammad Ilyas is requesting an update to the following information.',
            'body_information' => 'Here all previous and requested data will go,',
        ];
        $admin->notify(new DefaultApprovalNotification($user, $testDetail));
        Notification::assertSentTo(
            [$admin],
            DefaultApprovalNotification::class
        );
    }
}
