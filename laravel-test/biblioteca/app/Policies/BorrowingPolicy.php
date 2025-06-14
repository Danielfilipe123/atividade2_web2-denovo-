<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Borrowing;

class BorrowingPolicy
{
    /**
     * Create a new policy instance.
     */
   public function returnBook(User $user, Borrowing $borrowing)
{
    // Apenas o usuário que emprestou ou um administrador
    return in_array($user->role, ['admin', 'bibliotecario']);
}

}
