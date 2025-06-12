<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Book::class => BookPolicy::class,
        Author::class => AuthorPolicy::class, 
        Publisher::class => PublisherPolicy::class,
        Categories::class => CategoriesPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // outras autorizações se quiser...
    }
}

