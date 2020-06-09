<?php
require('../vendor/autoload.php');

use App\Models\Rota;
use App\Models\Shift;
use App\Routines\SingleManning;

$blackWidow = new Shift(['start_time' => '9am', 'end_time' => '5pm']);
$rota = new Rota();
$rota->addShift($blackWidow);

//Given black widow has one long shift on the day
//and no-one else is working
//She should get a full day of supplement

$singleManningCheck = new SingleManning($rota);
echo $singleManningCheck->doCalculateMinutes() === (8 * 60) ? 'pass' : 'fail';
echo PHP_EOL;