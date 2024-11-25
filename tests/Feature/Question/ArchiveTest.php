<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertSoftDeleted, deleteJson};

it('should be able to archive a question', function () {

    $user     = User::factory()->create();
    $question = Question::factory()->for($user)->create();

    Sanctum::actingAs($user);

    deleteJson(route('question.archive', $question))
        ->assertNoContent();

    assertSoftDeleted('questions', ['id' => $question->id]);

});

it('should allow that only the creator can archive');
