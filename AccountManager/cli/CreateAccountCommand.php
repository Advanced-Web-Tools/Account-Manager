<?php

namespace AccountManager\cli;

use AccountManager\service\AccountRecords;
use InvalidArgumentException;

final class CreateAccountCommand extends AccountCommand
{
    public function getCommand(): string
    {
        return 'account_create';
    }

    public function getHelp(): string
    {
        return 'Create an account. Usage: account_create username:alice password:"secret" email:alice@example.com firstname:Alice lastname:Smith permission:0';
    }

    public function getArguments(): array
    {
        return [
            'username' => 'Required. Unique username.',
            'password' => 'Required. Initial password, at most 72 bytes.',
            'email' => 'Required. Unique email address.',
            'firstname' => 'Required. First name; quote names containing spaces.',
            'lastname' => 'Required. Last name; quote names containing spaces.',
            'permission' => 'Optional. 0 Admin, 1 Moderator, 2 Author. Defaults to 0.',
        ];
    }

    protected function run(AccountRecords $records, array $input): string
    {
        foreach (['username', 'password', 'email', 'firstname', 'lastname'] as $field) {
            if (!array_key_exists($field, $input)) {
                throw new InvalidArgumentException('Provide username, password, email, firstname and lastname.');
            }
        }
        $id = $records->create([
            'username' => $input['username'],
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'permission_level' => $input['permission'] ?? '0',
            'new_password' => $input['password'],
            'password_confirmation' => $input['password'],
        ]);
        return 'Account #' . $id . ' created.';
    }
}
