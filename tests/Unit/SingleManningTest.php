<?php

namespace Tests\Feature;

use App\Models\Rota;
use App\Routines\SingleManning;
use App\Models\Shift;
use App\Models\ShiftBreak;
use PHPUnit\Framework\TestCase;

class SingleManningTest extends TestCase
{
    public function testSingleShift()
    {
        $rota = new Rota();
        $shift = new Shift(['start_time' => '9am', 'end_time' => '5pm']);
        $shift->addBreak(new ShiftBreak(['start_time' => '1pm', 'end_time' => '2pm']));
        $rota->addShift($shift);

        $singleManning = new SingleManning($rota);
        $this->assertEquals($shift->getShiftLengthMinutes(), $singleManning->doCalculateMinutes());
    }

    public function testGenerateShiftPattern()
    {
        $rota = new Rota();
        $shift2 = new Shift(['start_time' => '12pm', 'end_time' => '3:30pm']);
        $rota->addShift($shift2);

        $shift1 = new Shift(['start_time' => '9am', 'end_time' => '12pm']);
        $rota->addShift($shift1);

        $singleManning = new SingleManning($rota);

        $shiftPattern = $singleManning->generateShiftPattern();
        $expectedPattern = [
            ['action' => 'start', 'time' => $shift1->start_time],
            ['action' => 'end', 'time' => $shift1->end_time],
            ['action' => 'start', 'time' => $shift2->start_time],
            ['action' => 'end', 'time' => $shift2->end_time],
        ];
        $this->assertEquals($expectedPattern, $shiftPattern);
    }

    public function testTwoNonOverlappingShifts()
    {
        $rota = new Rota();
        $shift2 = new Shift(['start_time' => '12pm', 'end_time' => '3pm']);
        $rota->addShift($shift2);
        $shift1 = new Shift(['start_time' => '9am', 'end_time' => '12pm']);
        $rota->addShift($shift1);

        $singleManning = new SingleManning($rota);
        $this->assertEquals(6*60, $singleManning->doCalculateMinutes());
    }

    public function testTwoOverlappingShifts()
    {
        $rota = new Rota();

        $shift1 = new Shift(['start_time' => '9am', 'end_time' => '3pm']);
        $rota->addShift($shift1);

        $shift2 = new Shift(['start_time' => '12pm', 'end_time' => '3pm']);
        $rota->addShift($shift2);

        $singleManning = new SingleManning($rota);
        $this->assertEquals(3*60, $singleManning->doCalculateMinutes());
    }

    public function testTwoOverlappingShiftsWithBreak()
    {
        $rota = new Rota();

        $shift1 = new Shift(['start_time' => '9am', 'end_time' => '3pm']);
        $shift1->addBreak((new ShiftBreak(['start_time' => '12pm', 'end_time' => '12:15pm'])));
        $rota->addShift($shift1);

        $shift2 = new Shift(['start_time' => '12pm', 'end_time' => '3pm']);
        $rota->addShift($shift2);

        $singleManning = new SingleManning($rota);
        $this->assertEquals(3.25*60, $singleManning->doCalculateMinutes());
    }

    public function testTwoOverlappingShiftsWithBreakOverlap()
    {
        $rota = new Rota();

        $shift1 = new Shift(['start_time' => '9am', 'end_time' => '3pm']);
        $shift1->addBreak((new ShiftBreak(['start_time' => '12pm', 'end_time' => '12:15pm'])));
        $rota->addShift($shift1);

        $shift2 = new Shift(['start_time' => '12pm', 'end_time' => '3pm']);
        $shift2->addBreak((new ShiftBreak(['start_time' => '12:14pm', 'end_time' => '12:30pm'])));
        $rota->addShift($shift2);

        $singleManning = new SingleManning($rota);
        $this->assertEquals((180+14+15), $singleManning->doCalculateMinutes());
    }

    public function testThreeOverlappingShifts()
    {
        $rota = new Rota();

        $shift1 = new Shift(['start_time' => '9am', 'end_time' => '3pm']);
        $rota->addShift($shift1);

        $shift2 = new Shift(['start_time' => '12pm', 'end_time' => '4pm']);
        $rota->addShift($shift2);

        $shift3 = new Shift(['start_time' => '2pm', 'end_time' => '7pm']);
        $rota->addShift($shift3);

        $singleManning = new SingleManning($rota);
        //9 to 12, 4 to 7
        $this->assertEquals(6*60, $singleManning->doCalculateMinutes());
    }

    public function testTwoWithMiddayClosure()
    {
        $rota = new Rota();

        $shift1 = new Shift(['start_time' => '9am', 'end_time' => '5pm']);
        $shift1->addBreak((new ShiftBreak(['start_time' => '12pm', 'end_time' => '13:00'])));
        $rota->addShift($shift1);

        $shift2 = new Shift(['start_time' => '9am', 'end_time' => '5pm']);
        $shift2->addBreak((new ShiftBreak(['start_time' => '12pm', 'end_time' => '13:00'])));
        $rota->addShift($shift2);

        $singleManning = new SingleManning($rota);
        $this->assertEquals(0, $singleManning->doCalculateMinutes());
    }

    public function testOvernightShift()
    {
        $rota = new Rota();

        $shift1 = new Shift(['start_time' => '5pm', 'end_time' => '1am next day']);
        $rota->addShift($shift1);

        $singleManning = new SingleManning($rota);
        $this->assertEquals(8*60, $singleManning->doCalculateMinutes());
    }
}
