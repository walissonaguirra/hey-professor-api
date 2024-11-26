<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\{assertAuthenticatedAs, assertDatabaseHas, postJson};
use function PHPUnit\Framework\assertTrue;

it('should be able to register in the application', function () {

    postJson(route('register'), [
        'name'     => 'John Doe',
        'email'    => 'john@doe.com',
        'password' => 'password',
    ])->assertSessionHasNoErrors();

    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'john@doe.com',
    ]);

    $johnDoe = User::whereEmail('john@doe.com')->first();

    assertTrue(Hash::check('password', $johnDoe->password));

});

it('should log the new user in the system', function () {

    postJson(route('register'), [
        'name'     => 'John Doe',
        'email'    => 'john@doe.com',
        'password' => 'password',
    ])->assertStatus(200);

    $user = User::first();

    assertAuthenticatedAs($user);

});

describe('validations', function () {

    test('name', function ($rule, $value, $meta = []) {

        postJson(route('register'), ['name' => $value])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'name' => ["validation.$rule"],
            ]);

    })->with([
        'required' => ['required', ''],
        'min:3'    => ['min', 'AB'],
        'max:255'  => ['max', str_repeat('*', 256)],
    ]);

    test('email', function ($rule, $value) {

        postJson(route('register'), ['email' => $value])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'email' => "validation.$rule",
            ]);

    })->with([
        'required' => ['required', ''],
        'min:3'    => ['min', 'AB'],
        'max:255'  => ['max', str_repeat('*', 256)],
        'email'    => ['email', 'not-email'],
    ]);

    test('email with dataset "unique"', function () {

        $user = User::factory()->create();

        postJson(route('register'), ['email' => $user->email])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'email' => 'validation.unique',
            ]);

    });

    test('password', function ($rule, $value) {

        postJson(route('register'), ['password' => $value])
            ->assertJsonValidationErrors([
                'password' => "validation.$rule",
            ]);

    })->with([
        'required' => ['required', ''],
        'min:8'    => ['min', 'AB'],
        'max:40'   => ['max', str_repeat('*', 41)],
    ]);

});
