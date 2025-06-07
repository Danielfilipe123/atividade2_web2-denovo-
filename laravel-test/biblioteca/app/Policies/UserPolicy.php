<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'bibliotecario', 'cliente']);
    }

    public function view(User $user, User $model): bool
    {
        return in_array($user->role, ['admin', 'bibliotecario', 'cliente']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'bibliotecario']);
    }

    public function update(User $user, User $model): bool
    {
        // Somente admin pode editar usuários e papéis
        return in_array($user->role, ['admin', 'bibliotecario']);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    public function editRole(User $user): bool
    {
        return in_array($user->role, ['admin', 'bibliotecario']);
    }
}
