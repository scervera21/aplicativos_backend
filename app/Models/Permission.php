<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends SpatiePermission
{
    protected $table = 'security.permissions';  // Especifica el nombre y esquema de la tabla en la base de datos

    protected $fillable = [
        'name',
        'guard_name',
        'category',    // 'access' | 'action'
        'module',  // módulo asociado
        'status',
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
        return $this->belongsToMany(
            Role::class,
            config('permission.table_names.role_has_permissions', 'security.role_has_permissions'),
            'permission_id',
            'role_id'
        ); 
    }
}
