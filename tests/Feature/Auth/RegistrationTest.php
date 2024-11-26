<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\{assertDatabaseHas, postJson};
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

    assertTrue(Hash::check('password', $johnDoe->email));

});
