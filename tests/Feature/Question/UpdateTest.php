<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, putJson};

it('should be able to update a question', function () {

    $user     = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    putJson(route('question.update', $question), [
        'question' => 'Updated question?',
    ])
    ->assertStatus(200);

    assertDatabaseHas('questions', [
        'id'       => $question->id,
        'user_id'  => $user->id,
        'question' => 'Updated question?',
    ]);

});

describe('validation rules', function () {

    test('question::required', function () {

        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        putJson(route('question.update', $question), [
            'question' => '',
        ])->assertJsonValidationErrors([
            'question' => 'required',
        ]);
    });

    test('question::eding with question mark', function () {

        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        putJson(route('question.update', $question), [
            'question' => 'Updated question',
        ])->assertJsonValidationErrors([
            'question' => 'Isso não parace uma pergunta pois não temina com \'?\'',
        ]);
    });

    test('question::min character should be 10', function () {

        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        putJson(route('question.update', $question), [
            'question' => str_repeat('?', 9),
        ])->assertJsonValidationErrors([
            'question' => 'validation.min.string',
        ]);
    });

    test('question::should be unique', function () {

        $user     = User::factory()->create();
        $question = $user->questions()->create([
            'question' => 'Lorem ipsum jeremias?',
        ]);

        Sanctum::actingAs($user);

        putJson(route('question.update', $question), [
            'question' => 'Lorem ipsum jeremias?',
        ])->assertStatus(200);
    });
});

describe('security', function () {
    test('only the person who create the question can update the seme question', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $question = Question::factory()->create(['user_id' => $user2->id]);

        Sanctum::actingAs($user1);

        putJson(route('question.update', $question), [
            'question' => 'update question?',
        ])->assertForbidden();

        assertDatabaseHas('questions', [
            'id'       => $question->id,
            'question' => $question->question,
        ]);
    });
});
