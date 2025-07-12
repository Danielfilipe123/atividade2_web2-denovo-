<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing; 

use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    
    public function store(Request $request, Book $book)
{

    $limite = 5;

    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

     $jaEmprestado = Borrowing::where('book_id', $book->id)
                                ->whereNull('returned_at')
                                ->exists();

    if ($jaEmprestado) {
        return redirect()
            ->back()
            ->with('error', 'Este livro já se encontra emprestado.');
    }

     $quantidade = Borrowing::where('user_id', $request->user_id)
                                ->whereNull('returned_at')
                                ->count();
    
    if ($quantidade >=  $limite ) {
        return redirect()
            ->back()
            ->with('error', 'Este livro esta acima de 5');
    }

    $user = User::find($request->user_id);
    if ($user->debt > 0) {
    return redirect()->back()->with('erro', 'Usuário possui multas pendentes.');
}


    Borrowing::create([
        'user_id' => $request->user_id,
        'book_id' => $book->id,
        'borrowed_at' => now(),
        'due_at'=> now()->addDays(15),
    ]);

    return redirect()->route('books.show', $book)->with('success', 'Empréstimo registrado com sucesso.');
}

public function returnBook(Borrowing $borrowing)
{

    $this->authorize('returnBook', $borrowing);

    $today = now();
    $borrowing->returned_at = $today;

     if (!is_null($borrowing->due_at) && now()->gt($borrowing->due_at)) {
        $diasAtraso = $today->diffInDays($borrowing->due_at);
        $multa = $diasAtraso * 0.50;

        $borrowing->fine_amount = $multa;

        $user = $borrowing->user;
        $user->debt += $multa;
        $user->save();
    }

    $borrowing->save();

    return redirect()->back()->with('success', 'Livro devolvido com sucesso.');
}

public function userBorrowings(User $user)
{
    $borrowings = $user->books()->withPivot('borrowed_at', 'returned_at')->get();

    return view('users.borrowings', compact('user', 'borrowings'));
}


}
