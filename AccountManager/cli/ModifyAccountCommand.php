<?php

namespace AccountManager\cli;

use AccountManager\service\AccountRecords;

final class ModifyAccountCommand extends AccountCommand
{
    public function getCommand(): string
    {
        return 'account_modify';
    }

    public function getHelp(): string
    {
        return 'Modify supplied fields; other fields are preserved. Usage: account_modify id:12 email:new@example.com permission:1 password:"new secret"';
    }

    public function getArguments(): array
    {
        return [
            'id' => 'Required. ID of the account to modify.',
            'username' => 'Optional. New unique username.',
            'email' => 'Optional. New unique email address.',
            'firstname' => 'Optional. First name.',
            'lastname' => 'Optional. Last name.',
            'permission' => 'Optional. 0 Admin, 1 Moderator, 2 Author.',
            'password' => 'Optional. Replacement password, at most 72 bytes.',
        ];
    }

    protected function run(AccountRecords $records, array $input): string
    {
        $id = $this->accountId($input);
        unset($input['id']);
        if (array_key_exists('permission', $input)) {
            $input['permission_level'] = $input['permission'];
            unset($input['permission']);
        }
        $records->modify($id, $input);
        return 'Account #' . $id . ' modified.';
    }
}
