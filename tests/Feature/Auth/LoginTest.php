<?php

use App\Models\User;

use function Pest\Laravel\{assertAuthenticatedAs, postJson};

it('should be able to login', function () {

    $user = User::factory()->create([
        'name'     => 'John Doe',
        'email'    => 'john@doe.com',
        'password' => 'password',
    ]);

    postJson(route('login'), [
        'email'    => $user->email,
        'password' => 'password',
    ])->assertNoContent();

    assertAuthenticatedAs($user);

});

it('should check if the email and password is valid')->todo();

test('required fields')->todo();
