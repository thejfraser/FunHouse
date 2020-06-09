<?php

namespace Tests\Unit;

use App\Models\Shift;
use App\Models\ShiftBreak;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ShiftTest extends TestCase
{

    public function testStartTimeIsCarbon()
    {
        $shift = new Shift();
        $shift->start_time = '9am';
        $this->assertInstanceOf(Carbon::class, $shift->start_time);
        $this->assertEquals('9', $shift->start_time->format('G'));
    }

    public function testEndTimeIsCarbon()
    {
        $shift = new Shift();
        $shift->end_time = '5pm';
        $this->assertInstanceOf(Carbon::class, $shift->end_time);
        $this->assertEquals('17', $shift->end_time->format('G'));
    }

    public function testHasBreaks()
    {
        $shift = new Shift();
        $break = new ShiftBreak();
        $shift->addBreak($break);

        $this->assertIsArray($shift->breaks);
        $this->assertArrayHasKey(0, $shift->breaks);
        $this->assertInstanceOf(ShiftBreak::class, $shift->breaks[0]);
    }

    public function testElapsedTime()
    {
        $shift = new Shift();
        $shift->start_time = '9am';
        $shift->end_time = '10am';

        $this->assertEquals(60, $shift->getShiftLengthMinutes());
    }

    public function TestElapsedTimeWithBreak()
    {
        $shift = new Shift();
        $shift->start_time = '9am';
        $shift->end_time = '10am';
        $shiftBreak = new ShiftBreak();
        $shiftBreak->start_time = '9:45am';
        $shiftBreak->end_time = '10am';
        $shift->addBreak($shiftBreak);

        $this->assertEquals(45, $shift->getShiftLengthMinutes());
    }
}
