<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gerencia extends Model
{
    use HasFactory;

    protected $table = 'public.gerencias';

    protected $fillable = [
        'nombre_gerencia',
        'sigla',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class);     // Una gerencia tiene muchos usuarios
    }

    public function aplicativos()
    {
        return $this->hasMany(Aplicativo::class, 'gerencia_id'); // Una gerencia posee muchos aplicativos
    }

    public function actividadesParticipadas()
    {
        return $this->belongsToMany(Actividad::class, 'colaboradores', 'gerencia_id', 'actividad_id'); // Una gerencia puede colaborar en muchas actividades
    }
}
