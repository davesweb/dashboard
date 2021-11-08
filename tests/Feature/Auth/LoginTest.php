<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Tests\Feature\Auth;

use Davesweb\Dashboard\Models\User;
use Davesweb\Dashboard\Tests\TestCase;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @internal
 */
class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_redirects_to_login_page(): void
    {
        $response = $this->get(dashboard_route('index'));

        $response->assertRedirect(dashboard_route('login'));
    }

    public function test_it_shows_login_page(): void
    {
        $response = $this->get(dashboard_route('login'));

        $response->assertSuccessful();
        $response->assertViewIs('dashboard::auth.login');
    }

    /**
     * @dataProvider invalidCredentialsProvider
     */
    public function test_invalid_credentials_result_in_error(string $email, string $password, array $expectedErrorKeys): void
    {
        $response = $this->post(dashboard_route('login'), [
            'email'    => $email,
            'password' => $password,
        ], [
            'Referer' => dashboard_route('login'),
        ]);

        $response->assertRedirect(dashboard_route('login'));
        $response->assertSessionHasErrors($expectedErrorKeys);
    }

    public function test_valid_credentials_can_login(): void
    {
        User::factory()->create([
            'email'    => $email = 'test@user.com',
            'password' => resolve(Hasher::class)->make($password = 'password'),
        ]);

        $response = $this->post(dashboard_route('login'), [
            'email'    => $email,
            'password' => $password,
        ], [
            'Referer' => dashboard_route('login'),
        ]);

        $response->assertRedirect(dashboard_route('index'));
        $response->assertSessionDoesntHaveErrors();
    }

    public function invalidCredentialsProvider(): array
    {
        return [
            ['', 'password', ['email']],
            ['user@adres.nl', '', ['password']],
            ['invalid-email', 'password', ['email']],
            ['user@adres.nl', 'password', ['email']],
        ];
    }
}
