<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

it('should be able to list only publihed questions', function () {

    Sanctum::actingAs(User::factory()->create());

    $publish = Question::factory()->published()->create();
    $draft   = Question::factory()->draft()->create();

    $req = getJson(route('question.index'))->assertOk();

    $req->assertJsonFragment([
        'id'         => $publish->id,
        'question'   => $publish->question,
        'draft'      => $publish->draft,
        'created_by' => [
            'id'   => $publish->user->id,
            'name' => $publish->user->name,
        ],
        'created_at' => $publish->created_at->format('Y-m-d H:i'),
        'updated_at' => $publish->updated_at->format('Y-m-d H:i'),
    ])->assertJsonMissing([
        'question' => $draft->question,
    ]);

});

it('should be able to search for a question', function () {

    Sanctum::actingAs(User::factory()->create());

    Question::factory()->published()->create(['question' => 'First question?']);
    Question::factory()->published()->create(['question' => 'Secondary question?']);

    getJson(route('question.index', ['q' => 'first']))
        ->assertOk()
        ->assertJsonFragment(['question' => 'First question?'])
        ->assertJsonMissing(['question' => 'Secondary question?']);

});
