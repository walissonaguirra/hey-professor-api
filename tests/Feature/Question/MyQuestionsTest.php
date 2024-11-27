<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

it('should list only questions that the logged user has been created :: published', function () {

    $user                     = User::factory()->create();
    $userQuestion             = Question::factory()->published()->for($user)->create();
    $userDraftQuestion        = Question::factory()->draft()->for($user)->create();
    $anotherUserQuestion      = Question::factory()->published()->create();
    $anotherUserDraftQuestion = Question::factory()->draft()->create();

    Sanctum::actingAs($user);

    $req = getJson(route('my-questions', ['status' => 'published']))->assertOk();

    $req->assertJsonFragment([
        'id'         => $userQuestion->id,
        'question'   => $userQuestion->question,
        'draft'      => $userQuestion->draft,
        'created_by' => [
            'id'   => $user->id,
            'name' => $user->name,
        ],
        'created_at' => $userQuestion->created_at->format('Y-m-d H:i'),
        'updated_at' => $userQuestion->updated_at->format('Y-m-d H:i'),
    ])
    ->assertJsonMissing(['question' => $userDraftQuestion->question])
    ->assertJsonMissing(['question' => $anotherUserQuestion->question])
    ->assertJsonMissing(['question' => $anotherUserDraftQuestion->question]);

});

it('should list only questions that the logged user has been created :: draft', function () {

    $user                     = User::factory()->create();
    $userQuestion             = Question::factory()->published()->for($user)->create();
    $userDraftQuestion        = Question::factory()->draft()->for($user)->create();
    $anotherUserQuestion      = Question::factory()->published()->create();
    $anotherUserDraftQuestion = Question::factory()->draft()->create();

    Sanctum::actingAs($user);

    $req = getJson(route('my-questions', ['status' => 'draft']))->assertOk();

    $req->assertJsonFragment([
        'id'         => $userDraftQuestion->id,
        'question'   => $userDraftQuestion->question,
        'draft'      => $userDraftQuestion->draft,
        'created_by' => [
            'id'   => $user->id,
            'name' => $user->name,
        ],
        'created_at' => $userDraftQuestion->created_at->format('Y-m-d H:i'),
        'updated_at' => $userDraftQuestion->updated_at->format('Y-m-d H:i'),
    ])
    ->assertJsonMissing(['question' => $userQuestion->question])
    ->assertJsonMissing(['question' => $anotherUserQuestion->question])
    ->assertJsonMissing(['question' => $anotherUserDraftQuestion->question]);

});

it('should list only questions that the logged user has been created :: archived', function () {

    $user                        = User::factory()->create();
    $userQuestion                = Question::factory()->published()->for($user)->create();
    $userArchivedQuestion        = Question::factory()->archived()->for($user)->create();
    $userDraftQuestion           = Question::factory()->draft()->for($user)->create();
    $anotherUserQuestion         = Question::factory()->published()->create();
    $anotherUserDraftQuestion    = Question::factory()->draft()->create();
    $anotherUserArchivedQuestion = Question::factory()->archived()->create();

    Sanctum::actingAs($user);

    $req = getJson(route('my-questions', ['status' => 'archived']))->assertOk();

    $req->assertJsonFragment([
        'id'         => $userArchivedQuestion->id,
        'question'   => $userArchivedQuestion->question,
        'draft'      => $userArchivedQuestion->draft,
        'created_by' => [
            'id'   => $user->id,
            'name' => $user->name,
        ],
        'created_at' => $userArchivedQuestion->created_at->format('Y-m-d H:i'),
        'updated_at' => $userArchivedQuestion->updated_at->format('Y-m-d H:i'),
    ])
    ->assertJsonMissing(['question' => $userQuestion->question])
    ->assertJsonMissing(['question' => $userDraftQuestion->question])
    ->assertJsonMissing(['question' => $anotherUserQuestion->question])
    ->assertJsonMissing(['question' => $anotherUserDraftQuestion->question])
    ->assertJsonMissing(['question' => $anotherUserArchivedQuestion->question]);

});

test(
    'making sure that only draft, published, and archived statuses can be passed to the route',
    function (string $status, int $code) {

        $user                 = User::factory()->create();
        $userQuestion         = Question::factory()->published()->for($user)->create();
        $userArchivedQuestion = Question::factory()->archived()->for($user)->create();
        $userDraftQuestion    = Question::factory()->draft()->for($user)->create();

        Sanctum::actingAs($user);

        getJson(route('my-questions', ['status' => $status]))->assertStatus($code);

    }
)->with([
    'draft'     => ['draft', 200],
    'published' => ['published', 200],
    'archived'  => ['archived', 200],
    'thing'     => ['thing', 422],
]);
