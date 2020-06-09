<?php

namespace Tests\Unit;

use App\Models\ShiftBreak;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ShiftBreakTest extends TestCase
{
    public function testStartTimeIsCarbon()
    {
        $break = new ShiftBreak();
        $break->start_time = '9am';
        $this->assertInstanceOf(Carbon::class, $break->start_time);
        $this->assertEquals('9', $break->start_time->format('G'));
    }

    public function testEndTimeIsCarbon()
    {
        $break = new ShiftBreak();
        $break->end_time = '5pm';
        $this->assertInstanceOf(Carbon::class, $break->end_time);
        $this->assertEquals('17', $break->end_time->format('G'));
    }
}
