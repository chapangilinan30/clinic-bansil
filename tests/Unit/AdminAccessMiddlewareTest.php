<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

uses(Tests\TestCase::class);

function adminAccessRequest(User $user, string $uri): Request
{
    $request = Request::create($uri);
    $request->setUserResolver(fn () => $user);

    return $request;
}

it('allows an explicitly granted admin without changing their role', function () {
    $user = new User(['role' => 'doctor', 'is_admin' => true]);
    $this->actingAs($user);

    $response = (new AdminMiddleware)->handle(
        adminAccessRequest($user, '/admin/dashboard'),
        fn () => response('allowed')
    );

    expect($user->role)->toBe('doctor')
        ->and($response->getContent())->toBe('allowed');
});

it('allows users with the legacy admin role', function () {
    $user = new User(['role' => 'admin', 'is_admin' => false]);
    $this->actingAs($user);

    $response = (new AdminMiddleware)->handle(
        adminAccessRequest($user, '/admin/dashboard'),
        fn () => response('allowed')
    );

    expect($response->getContent())->toBe('allowed');
});

it('blocks staff roles without admin privilege from admin routes', function (string $role) {
    $user = new User(['role' => $role, 'is_admin' => false]);
    $this->actingAs($user);

    expect(fn () => (new AdminMiddleware)->handle(
        adminAccessRequest($user, '/admin/dashboard'),
        fn () => response('allowed')
    ))->toThrow(HttpException::class);
})->with(['doctor', 'clerk', 'staff']);

it('does not let admin privilege bypass unrelated role checks', function () {
    $this->actingAs(new User(['role' => 'clerk', 'is_admin' => true]));

    expect(fn () => (new RoleMiddleware)->handle(
        Request::create('/doctor/dashboard'),
        fn () => response('allowed'),
        'doctor'
    ))->toThrow(HttpException::class);
});
