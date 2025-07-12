<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use Hasfactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

   
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];

    }

    public function isAdmin()
{
    return $this->role === 'admin';
}

    public function books(){
        return $this->belongsToMany(Book::class, 'borrowings')
                ->withPivot('id','borrowed_at', 'due_at', 'returned_at', 'fine_amount')
                ->withTimestamps();

    }
    //Usei o tinker, tive que usar o Hasfactory aqui
}
