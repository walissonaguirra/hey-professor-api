<?php

use App\Http\Controllers\{QuestionController, RegisterController};
use Illuminate\Support\Facades\Route;

Route::post('register', RegisterController::class)->name('register');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('questions', [QuestionController::class, 'store'])->name('question.store');
    Route::put('questions/{question}', [QuestionController::class, 'update'])->name('question.update');
    Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('question.delete');
    Route::delete('questions/{question}/archive', [QuestionController::class, 'archive'])->name('question.archive');
    Route::put('questions/{question}/restore', [QuestionController::class, 'restore'])->name('question.restore');
    Route::put('questions/{question}/publish', [QuestionController::class, 'publish'])->name('question.publish');

});
