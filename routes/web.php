<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ApplyController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/applies', [ApplyController::class, 'index'])->name('applies.index');
Route::post('/applies', [ApplyController::class, 'store'])->name('applies.store');


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/posts/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::get('/posts/editId/{post}', [PostController::class, 'editId'])->name('posts.editId');
    Route::patch('/posts/editId/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/editId/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/applies/{apply}', [ApplyController::class, 'move'])->name('posts.move');
    Route::get('/applies/judge', [ApplyController::class, 'judge'])->name('applies.judge');
    Route::delete('/applies/judge/{apply}', [ApplyController::class, 'destroy'])->name('applies.destroy');
});

require __DIR__.'/auth.php';
