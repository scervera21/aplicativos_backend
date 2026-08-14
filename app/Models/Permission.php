<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends SpatiePermission
{
    protected $table = 'security.permissions';

    protected $fillable = [
        'name',
        'guard_name',
        'category',    // 'access' para ver módulos, 'action' para operaciones CRUD
        'module',  // URL del módulo asociado
        'status',  // Habilitado/Deshabilitado (boolean)
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions', 'permission_id', 'role_id'); 
    }
}
