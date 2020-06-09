<?php

namespace Tests\Unit;

use App\Models\Rota;
use App\Models\Shift;
use PHPUnit\Framework\TestCase;

class RotaTest extends TestCase
{
    public function testTakesShifts()
    {
        $rota = new Rota();
        $shift = new Shift();
        $rota->addShift($shift);

        $this->assertIsArray($rota->shifts);
        $this->assertArrayHasKey(0, $rota->shifts);
        $this->assertInstanceOf(Shift::class, $rota->shifts[0]);
    }
}
