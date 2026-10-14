<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleAplicativo extends Model
{
    use HasFactory;

    protected $table = 'public.detalles_aplicativos';

    protected $fillable = [
        'aplicativo_id',
        'fecha_inicio',
        'fecha_fin',
        'lenguajes_frontend',
        'lenguajes_backend',
        'bases_datos',
        'observaciones'
    ];

    public function aplicativos()
    {
        return $this->belongsTo(Aplicativo::class);     // Un detalle de aplicativo pertenece a un aplicativo
    }
}
