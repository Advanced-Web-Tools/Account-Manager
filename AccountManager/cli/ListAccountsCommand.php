<?php

namespace AccountManager\cli;

use AccountManager\service\AccountRecords;

final class ListAccountsCommand extends AccountCommand
{
    public function getCommand(): string
    {
        return 'account_list';
    }

    public function getHelp(): string
    {
        return 'Show all accounts in an ID-ordered table with profile details and permission levels. Usage: account_list';
    }

    public function getArguments(): array
    {
        return [];
    }

    protected function run(AccountRecords $records, array $input): string
    {
        $accounts = $records->all();
        if ($accounts === []) {
            return 'No accounts found.';
        }

        $headers = ['ID', 'Username', 'First name', 'Last name', 'Email', 'Permission'];
        $roles = [0 => 'Admin', 1 => 'Moderator', 2 => 'Author'];
        $rows = [];
        foreach ($accounts as $account) {
            $level = $account['permission_level'];
            $rows[] = array_map($this->cell(...), [
                $account['id'], $account['username'], $account['firstname'],
                $account['lastname'], $account['email'],
                $level . ' (' . ($roles[$level] ?? 'Unknown') . ')',
            ]);
        }

        $widths = array_map($this->width(...), $headers);
        foreach ($rows as $row) {
            foreach ($row as $column => $value) {
                $widths[$column] = max($widths[$column], $this->width($value));
            }
        }
        $border = '+' . implode('+', array_map(static fn(int $width): string => str_repeat('-', $width + 2), $widths)) . '+';
        $format = function (array $row) use ($widths): string {
            $cells = [];
            foreach ($row as $column => $value) {
                $cells[] = ' ' . $value . str_repeat(' ', $widths[$column] - $this->width($value)) . ' ';
            }
            return '|' . implode('|', $cells) . '|';
        };

        return implode(PHP_EOL, [
            $border, $format($headers), $border,
            ...array_map($format, $rows), $border,
            count($accounts) . (count($accounts) === 1 ? ' account.' : ' accounts.'),
        ]);
    }

    private function cell(mixed $value): string
    {
        // Stored profile text must not insert terminal control sequences or extra rows.
        return preg_replace('/[\p{Cc}]/u', ' ', (string)$value) ?? '';
    }

    private function width(string $value): int
    {
        if (function_exists('mb_strwidth')) {
            return mb_strwidth($value, 'UTF-8');
        }
        return preg_match_all('/./u', $value) ?: strlen($value);
    }
}
