<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, assertDatabaseMissing, deleteJson};

it('should be able to delete a question', function () {

    $user     = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    deleteJson(route('question.delete', $question))
        ->assertNoContent();

    assertDatabaseMissing('questions', [
        'id' => $question->id,
    ]);

});

it('should allow that only the creator can delete', function () {

    $user1    = User::factory()->create();
    $user2    = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user2->id]);

    Sanctum::actingAs($user1);

    deleteJson(route('question.delete', $question))
        ->assertForbidden();

    assertDatabaseHas('questions', [
        'id' => $question->id,
    ]);

});
