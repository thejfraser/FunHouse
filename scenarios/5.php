<?php
require('../vendor/autoload.php');

use App\Models\Rota;
use App\Models\Shift;
use App\Models\ShiftBreak;
use App\Routines\SingleManning;

$wolverine = new Shift(['start_time' => '9am', 'end_time' => '5pm']);
$juggernaut = new Shift(['start_time' => '10am', 'end_time' => '6pm']);
$juggernaut->addBreak(new ShiftBreak(['start_time' => '1pm', 'end_time' => '2pm']));
$rota = new Rota();
$rota->addShift($wolverine);
$rota->addShift($juggernaut);

//Given Wolverine and Juggernaut both have shifts
//When Wolverine and Juggernauts' shifts overlap for most of the day
//And Juggernaut takes a break to play with Deadpool at lunch
//Then Wolverine show receive a supplement for the time between the start of his and Juggernaut' shifts
//and Juggernaut show receive a supplement for the time between the end of Wolverine's shift and his shift
//and Wolverine show receive a supplement for the time Juggernaut was on break

$singleManningCheck = new SingleManning($rota);
echo $singleManningCheck->doCalculateMinutes() === ((3 * 60)) ? 'pass' : 'fail';
echo PHP_EOL;