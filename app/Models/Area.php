<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'security.areas';

    protected $fillable = [
        'nombre_area',
    ];

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }
}
