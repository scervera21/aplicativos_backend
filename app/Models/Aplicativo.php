<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aplicativo extends Model
{
    use HasFactory;
    protected $table = 'security.aplicativos';  // Especifica el nombre y esquema de la tabla en la base de datos

    protected $fillable = [
        'aplicativo',
        'tipo_software',
        'fecha_inicio',
        'fecha_final',
        'estatus',
        'pap',
        'pap_estatus',
        //'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // Un aplicativo pertenece a un usuario
    }
}
