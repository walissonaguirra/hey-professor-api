<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertNotSoftDeleted, assertSoftDeleted, putJson};

it('should be able to retore a question', function () {

    $user     = User::factory()->create();
    $question = Question::factory()->for($user)->create();
    $question->delete();

    assertSoftDeleted('questions', ['id' => $question->id]);

    Sanctum::actingAs($user);

    putJson(route('question.restore', $question))
        ->assertNoContent();

    assertNotSoftDeleted('questions', ['id' => $question->id]);

});

it('should allow that only creator can restore question', function () {

    $user1    = User::factory()->create();
    $user2    = User::factory()->create();
    $question = Question::factory()->for($user1)->create();
    $question->delete();

    assertSoftDeleted('questions', ['id' => $question->id]);

    Sanctum::actingAs($user2);

    putJson(route('question.restore', $question))
        ->assertForbidden();

    assertSoftDeleted('questions', ['id' => $question->id]);

});
