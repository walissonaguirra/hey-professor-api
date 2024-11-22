<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseMissing, deleteJson};

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

it('should allow that only the creator can delete');
