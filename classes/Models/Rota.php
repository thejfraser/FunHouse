<?php

namespace App\Models;

use App\BaseModel;

class Rota extends BaseModel
{
    protected $attributes = [
        'shifts' => []
    ];

    public function addShift(Shift $shift)
    {
        $this->attributes['shifts'][] = $shift;
    }

}
