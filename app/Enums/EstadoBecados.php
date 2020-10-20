<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class EstadoBecados extends Enum
{
    const ACTIVO =   1;
    const DESHABILITADO =   0;
    const REGISTRO_INCOMPLETO = 2;
}
