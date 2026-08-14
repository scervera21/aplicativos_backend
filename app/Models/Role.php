<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Role extends SpatieRole
{
    protected $table = 'security.roles';

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            config('permission.table_names.model_has_roles', 'security.model_has_roles'),
            'role_id',
            'model_id'
        );
    }

    public function permissions() : BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            config('permission.table_names.role_has_permissions', 'security.role_has_permissions'),
            'role_id',
            'permission_id'
        );
    }
}
