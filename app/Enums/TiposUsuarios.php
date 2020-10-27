<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class TiposUsuarios extends Enum
{
    const BECADO        =   0;
    const OPERADOR      =   1;
    const ADMINISTRADOR  = 2;
    const LECTOR_HUELLA =  4;
    const ROOT          = 99;
}
