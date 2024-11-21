<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, postJson};

it('should be able to store a new question', function () {

    $user = User::factory()->create();

    Sanctum::actingAs($user);

    postJson(route('question.store'), [
        'question' => 'Lorem ipsum Jeremias?',
    ])->assertStatus(201);

    assertDatabaseHas('questions', [
        'user_id'  => $user->id,
        'question' => 'Lorem ipsum Jeremias?',
    ]);

});

test('after creating new question, I need to make sure that it creates with status _draft_', function () {

    $user = User::factory()->create();

    Sanctum::actingAs($user);

    postJson(route('question.store'), [
        'question' => 'Lorem ipsum Jeremias?',
    ])->assertStatus(201);

    assertDatabaseHas('questions', [
        'question' => 'Lorem ipsum Jeremias?',
        'draft'    => true,
    ]);

});

describe('validation rules', function () {

    test('question::required', function () {

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        postJson(route('question.store'), [
            'question' => '',
        ])->assertJsonValidationErrors([
            'question' => 'required',
        ]);
    });

    test('question::eding with question mark', function () {

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        postJson(route('question.store'), [
            'question' => 'Question weihout a question mark',
        ])->assertJsonValidationErrors([
            'question' => 'Isso não parace uma pergunta pois não temina com \'?\'',
        ]);
    });

    test('question::min character should be 10', function () {

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        postJson(route('question.store'), [
            'question' => str_repeat('?', 9),
        ])->assertJsonValidationErrors([
            'question' => 'validation.min.string',
        ]);
    });

    test('question::should be unique', function () {

        $user = User::factory()->create();

        $user->questions()->create([
            'question' => 'Lorem ipsum jeremias?',
        ]);

        Sanctum::actingAs($user);

        postJson(route('question.store'), [
            'question' => 'Lorem ipsum jeremias?',
        ])->assertJsonValidationErrors([
            'question' => 'validation.unique',
        ]);
    });
});

test('after creating we should return a status 201 with the created question', function () {

    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $request = postJson(route('question.store'), [
        'question' => 'Lorem ipsum Jeremias?',
    ])->assertStatus(201);

    $question = Question::latest()->first();

    $request->assertJson([
        'data' => [
            'id'         => $question->id,
            'question'   => $question->question,
            'draft'      => $question->draft,
            'created_by' => [
                'id'   => $user->id,
                'name' => $user->name,
            ],
            'created_at' => $question->created_at->format('Y-m-d H:i'),
            'updated_at' => $question->updated_at->format('Y-m-d H:i'),
        ],
    ]);

});
