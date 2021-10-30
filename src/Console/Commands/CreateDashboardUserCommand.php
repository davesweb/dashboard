<?php

namespace Davesweb\Dashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Validation\ValidationException;
use Davesweb\Dashboard\Actions\Fortify\CreateNewUser;

class CreateDashboardUserCommand extends Command
{
    use ConfirmableTrait;

    /**
     * {@inheritdoc}
     */
    protected $signature = 'dashboard:user';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a new user for the dashboard.';

    public function handle(): int
    {
        if (!$this->confirmToProceed()) {
            return parent::SUCCESS;
        }

        $name            = $this->ask('What is the name of the new user?');
        $email           = $this->ask('What is the email address of the new user?');
        $password        = $this->secret('What is the password for the new user?');
        $passwordConfirm = $this->secret('Please confirm the password.');

        try {
            $user = (new CreateNewUser())->create([
                'name'                  => $name,
                'email'                 => $email,
                'password'              => $password,
                'password_confirmation' => $passwordConfirm,
            ]);

            $this->info('The user has been created successfully.');

            return parent::SUCCESS;
        } catch (ValidationException $e) {
            $this->error($e->getMessage());

            foreach ($e->errors() as $field => $errors) {
                $this->error(implode(PHP_EOL, $errors));
            }

            return parent::FAILURE;
        }
    }
}
