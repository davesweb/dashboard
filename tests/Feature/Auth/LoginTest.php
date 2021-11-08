<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Tests\Feature\Auth;

use Davesweb\Dashboard\Tests\TestCase;

/**
 * @internal
 */
class LoginTest extends TestCase
{
    public function test_it_redirects_to_login_page(): void
    {
        $response = $this->get(dashboard_route('index'));

        $response->assertRedirect(dashboard_route('login'));
    }
}
