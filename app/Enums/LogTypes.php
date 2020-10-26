<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class LogTypes extends Enum
{
    const DEBUG =   1;
    const INFO =   2;
    const WARNING = 3;
    const ERROR = 4;
}
