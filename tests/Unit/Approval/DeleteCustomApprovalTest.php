<?php

namespace Tests\Unit;

use App\Domain\Approval\Models\Approval;
use Tests\TestCase;

class DeleteCustomApprovalTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function createCustomApproval()
    {
        return $approval = factory(Approval::class)->create();
    }

    public function testDeleteCustomApproval()
    {
        $approval = $this->createCustomApproval();
        $response = $this->call('DELETE', 'en/approvals/'.$approval->id, ['_token' => csrf_token()]);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue($approval->forceDelete());
        $this->assertDatabaseMissing('approvals', ['id' => $approval->id]);
    }
}
