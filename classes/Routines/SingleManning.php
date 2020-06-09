<?php

namespace App\Routines;

use App\Models\Rota;
use App\Models\Shift;

class SingleManning
{
    const ACTION_START = 'start';
    const ACTION_END = 'end';
    protected $rota;
    protected $shifts;

    public function __construct(Rota $rota)
    {
        $this->rota = $rota;
        $this->shifts = $rota->shifts;
    }

    public function doCalculateMinutes()
    {
        if ($this->checkIsSingleShift()) {
            return $this->rota->shifts[0]->getShiftLengthMinutes();
        }

        $totalSingleMannedHours = 0;
        $employeesOn = 0;
        $lastActionTime = 0;

        foreach ($this->generateShiftPattern() as $pattern) {
            $action = $pattern['action'];
            $time = $pattern['time'];

            switch ($action) {
                case 'end':
                    $employeesOn--;
                    break;
                case 'start':
                    $employeesOn++;
                    break;
            }

            if (
                ($employeesOn === 2 && $action === 'start')
                ||
                ($employeesOn === 0 && $action === 'end')
            ) {
                $totalSingleMannedHours += $time->diffInMinutes($lastActionTime);
            }

            $lastActionTime = $time;
        }

        return $totalSingleMannedHours;
    }

    private function checkIsSingleShift()
    {
        return count($this->rota->shifts) === 1;
    }

    public function generateShiftPattern()
    {
        $shiftPattern = [];
        $this->sortShiftsByStartTime();
        foreach ($this->shifts as $shift) {
            $startTime = $shift->start_time;
            $endTime = $shift->end_time;
            $shiftPattern[] = ['action' => self::ACTION_START, 'time' => $startTime];
            $shiftPattern[] = ['action' => self::ACTION_END, 'time' => $endTime];

            if (count($shift->breaks)) {
                foreach ($shift->breaks as $break) {
                    $startTime = $break->start_time;
                    $endTime = $break->end_time;
                    //this reverses because a break start is a shift end, technically.
                    $shiftPattern[] = ['action' => self::ACTION_END, 'time' => $startTime];
                    $shiftPattern[] = ['action' => self::ACTION_START, 'time' => $endTime];
                }
            }
        }
        $this->sortShiftPatternByTime($shiftPattern);
        return $shiftPattern;
    }

    public function sortShiftsByStartTime()
    {
        usort($this->shifts, function (Shift $a, Shift $b) {
            $aU = $a->start_time->format('U');
            $bU = $b->start_time->format('U');
            if ($aU === $bU) {
                return 0;
            }

            return $aU > $bU ? 1 : -1;
        });
    }

    private function sortShiftPatternByTime(&$shiftPattern)
    {
        usort($shiftPattern, function (array $a, array $b) {
            $aT = $a['time']->format('U');
            $bT = $b['time']->format('U');
            if ($aT === $bT) {
                return 0;
            }

            return $aT > $bT;
        });
    }

}
