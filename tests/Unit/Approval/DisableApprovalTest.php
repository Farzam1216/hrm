<?php

namespace Tests\Unit;

use App\Domain\Approval\Models\Approval;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisableApprovalTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_approval_is_disabled()
    {
        $approval = factory(Approval::class)->create();
        $this->assertTrue($approval->forceDelete());
        $this->assertEquals(0, $approval->status);
    }

    public function test_approval_does_not_exists()
    {
        $approval = factory(Approval::class)->create();
        $this->assertTrue($approval->forceDelete());
        $this->assertDatabaseMissing('approvals', $approval->toArray());
    }
}
