<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatiePermission;


class Permission extends SpatiePermission
{
    use HasFactory;

    protected $fillable = ['name', 'guard_name', 'description'];
    // Assurez-vous que le nom de la connexion est correct dans le fichier de config pour les rôles/permissions
    protected $guard_name = 'web';

     /**
     * A permission can be applied to roles.
     */
     public function roles() : \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_permissions'),
            'permission_id',
            'role_id'
        );
    }

}
