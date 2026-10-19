<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends SpatiePermission
{
    protected $table = 'security.permissions';  // Especifica el nombre y esquema de la tabla en la base de datos

    protected $fillable = [
        'name',
        'guard',
        'module',
        'status'
    ];

    protected $attributes = [
        'guard' => 'api'
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
