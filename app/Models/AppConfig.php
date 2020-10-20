<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AppConfig
 * @package App\Models
 *
 * @property int max_porciones_becado
 * @property int max_porciones_quirofano
 *
 *
 */
class AppConfig extends Model
{
    use HasFactory;
    protected $table = "app_config";

    # Definimos los valores por defecto
    protected $attributes = [
        'max_porciones_becado' => 3,
        'max_porciones_quirofano' => 100
        // ...
    ];

    // indicamos las columnas que no se deberian modificar desde un create()
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at'
    ];

    public static function getConfig()
    {
        $config = AppConfig::first();

        if($config === null){
            // no existe el registro de configuracion
            $config = self::_crearConfiguracionDefecto();
        }

        return $config;
    }

    /**
     * Crea un registro del modelo con los valores por defecto.
     * @return AppConfig|Model
     */
    private static function _crearConfiguracionDefecto()
    {
        return self::create();
    }


}
