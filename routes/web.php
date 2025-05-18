<?php

use App\Http\Controllers\Controller\Api\AuthorController;
use App\Http\Controllers\Controller\BlogPostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('blog-posts.index');
    }

    return Inertia::render('auth/Login');

})->name('home');



Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');




Route::middleware(['auth','verified'])->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('blog-posts', BlogPostController::class)->names('blog-posts')->except(['show']);

});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
