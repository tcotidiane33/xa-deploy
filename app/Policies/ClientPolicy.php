<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view clients');
    }

    public function view(User $user, Client $client)
    {
        return $user->hasPermissionTo('view clients');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create clients');
    }

    public function update(User $user, Client $client)
    {
        return $user->hasPermissionTo('edit clients');
    }

    public function delete(User $user, Client $client)
    {
        return $user->hasPermissionTo('delete clients');
    }
}
