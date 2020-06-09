<?php


namespace App\Traits;

Trait ElapsedTimeTrait
{
    public function getElapsedTime()
    {
        return $this->end_time->diffInMinutes($this->start_time);
    }
}
