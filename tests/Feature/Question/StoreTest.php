<?php

use App\Models\User;
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

test('with the creation of the question, we need to make sure that it creates with status _draft_');

describe('validation rules', function () {
    test('question::required');
    test('question::eding with question mark');
    test('question::min character should be 10');
    test('question::should be unique');
});

test('after creating we should return a status 201 with the created question');
