<?php

namespace App\Models;

use App\BaseModel;
use App\Traits\ElapsedTimeTrait;

class ShiftBreak extends BaseModel
{
    use ElapsedTimeTrait;

    protected $attributes = [
        'start_time' => '',
        'end_time' => '',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

}
