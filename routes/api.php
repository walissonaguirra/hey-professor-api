<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\{LogoutController, RegisterController};
use App\Http\Controllers\{MyQuestionController, QuestionController};
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('register', RegisterController::class)->name('register');
    Route::post('login', LoginController::class)->name('login');
});

Route::middleware(['web', 'auth'])->post('logout', LogoutController::class)->name('logout');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('my-questions/{status}', MyQuestionController::class)->name('my-questions');

    Route::get('questions', [QuestionController::class, 'index'])->name('question.index');
    Route::post('questions', [QuestionController::class, 'store'])->name('question.store');
    Route::put('questions/{question}', [QuestionController::class, 'update'])->name('question.update');
    Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('question.delete');
    Route::delete('questions/{question}/archive', [QuestionController::class, 'archive'])->name('question.archive');
    Route::put('questions/{question}/restore', [QuestionController::class, 'restore'])->name('question.restore');
    Route::put('questions/{question}/publish', [QuestionController::class, 'publish'])->name('question.publish');
    Route::get('questions/export', [QuestionController::class, 'export'])->name('question.export');
});
