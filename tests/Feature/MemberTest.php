<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Member;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MemberTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_get_available_session_days()
    {
        $member = Member::find(228);
        $this->assertNotNull($member, 'Member with ID 228 does not exist.');
        $available_days = $member->getAvailableSessionDay('2025-01-01', '2025-01-31');

        $this->assertIsInt($available_days);
        $this->assertEquals(8, $available_days, 'The available session days for the member are not as expected.');
    }

    public function test_get_holiday_count()
    {
        $member = Member::find(228);
        $this->assertNotNull($member, 'Member with ID 228 does not exist.');
        $holiday_count = $member->getHolidayCount('2025-01-01', '2025-01-31');

        $this->assertIsInt($holiday_count);
        $this->assertEquals(1, $holiday_count, 'The holiday count for the member is not as expected.');
    }

}
