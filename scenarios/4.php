<?php
require('../vendor/autoload.php');

use App\Models\Rota;
use App\Models\Shift;
use App\Routines\SingleManning;

$wolverine = new Shift(['start_time' => '9am', 'end_time' => '5pm']);
$cyclops = new Shift(['start_time' => '10am', 'end_time' => '2pm']);
$rota = new Rota();
$rota->addShift($wolverine);
$rota->addShift($cyclops);

//Given Wolverine and Cyclops both have shifts
//When Wolverine works all day, but cyclops covers a portion of the shift
//Then Wolverine show receive a supplement for the time between the start of his and Cyclops' shifts
//and Wolverine show receive a supplement for the time between the end of Cyclops' shift and his shift

$singleManningCheck = new SingleManning($rota);
echo $singleManningCheck->doCalculateMinutes() === (60 + (3 * 60)) ? 'pass' : 'fail';
echo PHP_EOL;