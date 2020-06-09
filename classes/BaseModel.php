<?php

namespace App;

use Carbon\Carbon;

abstract class BaseModel
{
    protected $attributes = [];
    protected $casts = [];

    public function __construct($args = false)
    {
        if (is_array($args)) {
            foreach ($args as $arg => $value) {
                $this->$arg = $value;
            }
        }
    }

    public function __get($name)
    {
        $value = $this->attributes[$name];

        if (isset($this->casts[$name])) {
            switch ($this->casts[$name]) {
                case 'datetime':
                    if (!$value instanceof Carbon) {
                        $value = Carbon::parse($value);
                    }
                    break;
            }
        }

        return $value;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }


}