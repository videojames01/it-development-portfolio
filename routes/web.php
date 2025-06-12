<?php

use App\Models\Task;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

Route::resource('tasks', TaskController::class)
    ->except('index');

// Error routes
Route::get('/server-error', function () {
    abort(500);
});
