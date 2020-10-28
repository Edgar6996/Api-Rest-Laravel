<?php

namespace App\Models;

use App\Models\Becado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Huella
 * @package App\Models
 * @mixin  \Eloquent
 */
class Huella extends Model
{
    use HasFactory;

    protected $table = 'huellas';

    # Atributos que se pueden setear con create()
    protected $fillable = [
        'size_template', 'img_width', 'img_height',
        'template_huella', 'img_huella'
    ];




    public function becado(){
    	return $this->hasOne(Becado::class, 'becado_id');
    }
}
