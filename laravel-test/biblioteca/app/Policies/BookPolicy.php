<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookPolicy
{
    
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'bibliotecario', 'cliente']);
    }

    public function view(User $user, Book $model): bool
    {
        return in_array($user->role, ['admin', 'bibliotecario', 'cliente']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'bibliotecario']);
    }

    public function update(User $user, Book $model): bool
    {
        // Somente admin pode editar usuários e papéis
        return in_array($user->role, ['admin', 'bibliotecario']);
    }

    public function delete(User $user, Book $model): bool
    {
        return $user->role === 'admin';
    }

    public function editRole(User $user): bool
    {
        return in_array($user->role, ['admin', 'bibliotecario']);
    }
}
