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

it('should check if the email and password is valid', function ($email, $password) {

    User::factory()->create([
        'name'     => 'John Doe',
        'email'    => 'john@doe.com',
        'password' => 'password',
    ]);

    postJson(route('login'), [
        'email'    => $email,
        'password' => $password,
    ])->assertUnauthorized();

})->with([
    'wrong email'    => ['wrong@email.com', 'password'],
    'wrong password' => ['john@doe.com', 'wrong-password'],
]);

test('required fields', function () {
    postJson(route('login'), [
        'email'    => '',
        'password' => '',
    ])->assertJsonValidationErrors([
        'email'    => ['validation.required'],
        'password' => ['validation.required'],
    ]);
});
