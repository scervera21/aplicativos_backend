<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aplicativo extends Model
{
    use HasFactory;
    
    protected $table = 'public.aplicativos';

    protected $fillable = [
        'aplicativo',
        'alias',
        'responsable',
        'pap',
        'estatus',
        'descripcion',
        'gerencia_id'
    ];

    public function detallesAplicativos()
    {
        return $this->hasOne(DetalleAplicativo::class);     // Un aplicativo tiene un detalle
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class);            // Un aplicativo tiene muchas actividades
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable');   // Un aplicativo tiene un responsable
    }
}
