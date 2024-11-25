<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, putJson};

it('should be able to publish a question', function () {

    $user     = User::factory()->create();
    $question = Question::factory()->for($user)->create();

    Sanctum::actingAs($user);

    putJson(route('question.publish', $question))
        ->assertNoContent();

    assertDatabaseHas('questions', [
        'id'    => $question->id,
        'draft' => false,
    ]);

});

it('should allow that only creator can publish question', function () {

    $user1    = User::factory()->create();
    $user2    = User::factory()->create();
    $question = Question::factory()->for($user1)->create(['draft' => true]);

    assertDatabaseHas('questions', [
        'id'    => $question->id,
        'draft' => true,
    ]);

    Sanctum::actingAs($user2);

    putJson(route('question.publish', $question))
        ->assertForbidden();

    assertDatabaseHas('questions', [
        'id'    => $question->id,
        'draft' => true,
    ]);

})->todo();

it('should only publish when the question is on status draft', function () {

    $user     = User::factory()->create();
    $question = Question::factory()->for($user)->create(['draft' => false]);

    putJson(route('question.publish', $question))
        ->assertNotfound();

    assertDatabaseHas('questions', [
        'id'    => $question->id,
        'draft' => false,
    ]);

})->todo();
