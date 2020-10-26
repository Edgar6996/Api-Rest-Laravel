<?php


namespace App\Models;


use App\Enums\LogTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppLogs extends Model
{
    use HasFactory;

    protected $table = 'logs';
    protected $fillable = [
        'tipo',
        'titulo',
        'data'
    ];

    public static function add(string $titulo, int $tipo = LogTypes::DEBUG, array $data = null)
    {
        if($data != null){
            $data = json_encode($data);
        }

        self::create([
            'tipo' => $tipo,
            'titulo' => $titulo,
            'data' => $data
        ]);
    }

}
