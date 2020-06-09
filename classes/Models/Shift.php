<?php

namespace App\Models;

use App\BaseModel;
use App\Traits\ElapsedTimeTrait;

class Shift extends BaseModel
{
    use ElapsedTimeTrait;

    protected $attributes = [
        'start_time' => '',
        'end_time' => '',
        'breaks' => []
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function addBreak(ShiftBreak $break)
    {
        $this->attributes['breaks'][] = $break;
    }

    public function getShiftLengthMinutes()
    {
        $elapsedMinutes = $this->getElapsedTime();

        if (count($this->breaks) > 0) {
            foreach ($this->breaks as $break) {
                $elapsedMinutes -= $break->getElapsedTime();
            }
        }

        return $elapsedMinutes;
    }
}
