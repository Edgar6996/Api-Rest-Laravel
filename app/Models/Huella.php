<?php

namespace App\Models;

use App\Models\Becado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Huella extends Model
{
    use HasFactory;

    protected $table = 'huellas';

    public function becado(){
    	return $this->hasOne(Becado::class, 'becado_id');
    }
}
