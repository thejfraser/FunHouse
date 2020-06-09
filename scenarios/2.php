<?php
require('../vendor/autoload.php');

use App\Models\Rota;
use App\Models\Shift;
use App\Routines\SingleManning;

$blackWidow = new Shift(['start_time' => '9am', 'end_time' => '2pm']);
$thor = new Shift(['start_time' => '2pm', 'end_time' => '9pm']);
$rota = new Rota();
$rota->addShift($blackWidow);
$rota->addShift($thor);

//Given black widow and thor both have shifts
//When they don't overlap
//Then black widow show receive a full supplement
//and Thor should receive a full supplement

$singleManningCheck = new SingleManning($rota);
echo $singleManningCheck->doCalculateMinutes() === (12 * 60) ? 'pass' : 'fail';
echo PHP_EOL;