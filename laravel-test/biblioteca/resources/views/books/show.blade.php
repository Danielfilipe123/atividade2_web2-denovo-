@extends('layouts.app')

@section('content')



<div class="container">
    <h1 class="my-4">Detalhes do Livro</h1>

    
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    <div class="card">
        <div class="card-header">
            <strong>Título:</strong> {{ $book->title }}
        </div>

<h1>{{ $book->title }}</h1>

<div class="card-body p-3 d-flex flex-row align-items-start">
@if($book->image)
        <img 
            src="{{ asset('storage/' . $book->image) }}" 
            alt="Capa do livro {{ $book->title }}" 
            class="img-thumbnail border-end border-white pe-3 me-3" 
            style="max-width: 200px; height: auto;"
        >
    @else
        <img 
            src="{{ asset('storage/default/default-book.png') }}" 
            alt="Imagem padrão do livro" 
            class="img-thumbnail border-end border-white pe-3 me-3" 
            style="max-width: 200px; height: auto;"
        >
    @endif

</div>

<div>
        <div class="card-body">
            <p><strong>Autor:</strong>
                <a href="{{ route('authors.show', $book->author->id) }}">
                    {{ $book->author->name }}
                </a>
            </p>
            <p><strong>Editora:</strong>
                <a href="{{ route('publishers.show', $book->publisher->id) }}">
                    {{ $book->publisher->name }}
                </a>
            </p>
            <p><strong>Categoria:</strong>
                <a href="{{ route('categories.show', $book->category->id) }}">
                    {{ $book->category->name }}
                </a>
            </p>
               <p><strong>Publicado:</strong>
                <a href="{{ route('books.show', $book->author->id) }}">
                    {{ $book->author->birth_date }}
                </a>
            </p>
        </div>
    </div>
<!-- Formulário para Empréstimos -->
<div class="card mb-4">
    <div class="card-header">Registrar Empréstimo</div>
    <div class="card-body">
        <form action="{{ route('books.borrow', $book) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="user_id" class="form-label">Usuário</label>
                <select class="form-select" id="user_id" name="user_id" required>
                    <option value="" selected>Selecione um usuário</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">Registrar Empréstimo</button>
        </form>

    </div>
</div>

<!-- Histórico de Empréstimos -->
<div class="card">
    <div class="card-header">Histórico de Empréstimos</div>
    <div class="card-body">
        @if($book->users->isEmpty())
            <p>Nenhum empréstimo registrado.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Data de Empréstimo</th>
                        <th>Data de Devolução</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
    @foreach($book->users as $user)
    <tr>
    <td>
        <a href="{{ route('users.show', $user->id) }}">
            {{ $user->name }}
        </a>
    </td>
    <td>{{ $user->pivot->borrowed_at }}</td>
    <td>{{ $user->pivot->returned_at ?? 'Em Aberto' }}</td>
    <td>


   @if (is_null($user->pivot->returned_at))
@if (!is_null($user->pivot->due_at) && now()->gt($user->pivot->due_at) && is_null($user->pivot->returned_at))
    <span class="text-danger">Atrasado</span>
        <small>
    Atrasado há {{ \Carbon\Carbon::parse($user->pivot->due_at)->diffInDays(\Carbon\Carbon::parse($user->pivot->returned_at), false) }} dia(s)
     Multa: R$ {{ number_format($user->pivot->fine_amount, 2, ',', '.') }}
</small>

    @else
        <span class="text-success">No prazo</span>
    @endif
@else
    @if (!is_null($user->pivot->due_at) && $user->pivot->returned_at > $user->pivot->due_at)
        <span class="text-warning">Entregue com Atraso</span><br>
@php
   $dueAt = \Carbon\Carbon::parse($user->pivot->due_at);
$returnedAt = \Carbon\Carbon::parse($user->pivot->returned_at);

$diasAtraso = 0;
if ($returnedAt->gt($dueAt)) {
    $diasAtraso = $returnedAt->diffInDays($dueAt); // positivo, só se for atraso
}

@endphp

Atraso de {{ $diasAtraso }} dia(s)

    @else
        <span class="text-muted">Devolvido no Prazo</span>
    @endif
@endif

    </td>
    <td>
        @if(is_null($user->pivot->returned_at))
            <form action="{{ route('borrowings.return', $user->pivot->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button class="btn btn-warning btn-sm">Devolver</button>
            </form>
        @endif
    </td>
</tr>

    @endforeach
</tbody>
            </table>
        @endif
    </div>
</div>

    <a href="{{ route('books.index') }}" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

@endsection

