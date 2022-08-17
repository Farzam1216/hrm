<?php

namespace Tests\Unit;

use App\Domain\Approval\Models\Approval;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DefaultApprovalsTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
    /**
     * A basic feature test example.
     */
    public function testApprovalExists()
    {
        $approval = factory(Approval::class)->create();
        $this->assertNotEmpty($approval);
        $this->assertTrue($approval->forceDelete());
    }

    public function testDefaultApprovalsCount()
    {
        $customApprovalTypeID = 3;
        $numberOfDefaultApprovalsCount = 6;
        $defaultApprovalsCount = Approval::where('approval_type_id', '!=', $customApprovalTypeID)->get();
        $this->assertEquals($numberOfDefaultApprovalsCount, $defaultApprovalsCount->count());
    }
}
