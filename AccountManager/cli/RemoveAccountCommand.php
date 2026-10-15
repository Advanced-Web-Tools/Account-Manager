<?php

namespace AccountManager\cli;

use AccountManager\service\AccountRecords;

final class RemoveAccountCommand extends AccountCommand
{
    public function getCommand(): string
    {
        return 'account_remove';
    }

    public function getHelp(): string
    {
        return 'Permanently remove an account. Usage: account_remove id:12';
    }

    public function getArguments(): array
    {
        return ['id' => 'Required. ID of the account to remove.'];
    }

    protected function run(AccountRecords $records, array $input): string
    {
        $id = $this->accountId($input);
        $records->remove($id);
        return 'Account #' . $id . ' removed.';
    }
}
