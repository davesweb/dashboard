<?php

namespace Davesweb\Dashboard\Models;

use Illuminate\Support\Carbon;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int         $id
 * @property string      $name
 * @property string      $email
 * @property null|Carbon $email_verified_at
 * @property string      $password
 * @property string      $two_factor_secret
 * @property array       $two_factor_recovery_codes
 * @property string      $remember_token
 */
class User extends Authenticatable
{
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('dashboard.users.table');
    }
}
