<?php
require('../vendor/autoload.php');

use App\Models\Rota;
use App\Models\Shift;
use App\Routines\SingleManning;

$wolverine = new Shift(['start_time' => '9am', 'end_time' => '2pm']);
$gomora = new Shift(['start_time' => '10am', 'end_time' => '4pm']);
$rota = new Rota();
$rota->addShift($wolverine);
$rota->addShift($gomora);

//Given Wolverine and Gomora both have shifts
//When they overlap for most of the day
//Then Wolverine show receive a supplement for the time between the start of his shift and the start of Gomoras' shift
//and Gomora show receive a supplement for the time between the end of Wolverine's shift and the end of her shift

$singleManningCheck = new SingleManning($rota);
echo $singleManningCheck->doCalculateMinutes() === (60 + (2 * 60)) ? 'pass' : 'fail';
echo PHP_EOL;