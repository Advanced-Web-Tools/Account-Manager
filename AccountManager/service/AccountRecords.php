<?php

namespace AccountManager\service;

use admin\AdminPassword;
use database\DatabaseManager;
use database\trait\DoNotCache;
use InvalidArgumentException;

/** Shared persistence and validation. Callers enforce dashboard or console access. */
final class AccountRecords
{
    private DatabaseManager $database;
    private const PROFILE_FIELDS = ['firstname', 'lastname', 'username', 'email'];

    public function __construct(?DatabaseManager $database = null)
    {
        $this->database = $database ?? new class extends DatabaseManager { use DoNotCache; };
    }

    /** Public account fields only; password hashes and session tokens are excluded. */
    public function all(): array
    {
        return $this->database->table('awt_admin')
            ->select(['id', 'username', 'firstname', 'lastname', 'email', 'permission_level'])
            ->orderBy('id')
            ->get();
    }

    public function create(array $input): int
    {
        $values = $this->profileValues($input);
        $level = $this->permission($input['permission_level'] ?? null);
        $password = $this->password($input);
        $id = $this->database->table('awt_admin')->insert($values + [
            'permission_level' => $level,
            'password' => AdminPassword::hash($password),
            'token' => bin2hex(random_bytes(64)),
            'profile_picture' => null,
        ])->executeInsert();
        if (!$id) {
            throw new InvalidArgumentException('Unable to create the account. Please try again.');
        }
        return (int)$id;
    }

    /** Apply a partial change in one update; validate every supplied field first. */
    public function modify(int $id, array $input): void
    {
        $allowed = [...self::PROFILE_FIELDS, 'permission_level', 'password'];
        if ($input === [] || array_diff(array_keys($input), $allowed)) {
            throw new InvalidArgumentException('Provide at least one supported account field.');
        }
        $current = $this->find($id);
        $values = [];
        $profile = array_intersect_key($input, array_flip(self::PROFILE_FIELDS));
        if ($profile !== []) {
            $values = $this->profileValues(array_replace($current, $profile), $id);
        }
        if (array_key_exists('permission_level', $input)) {
            $values['permission_level'] = $this->permission($input['permission_level']);
        }
        if (array_key_exists('password', $input)) {
            $password = $this->password(['new_password' => $input['password'], 'password_confirmation' => $input['password']]);
            $values['password'] = AdminPassword::hash($password);
        }
        $changed = array_filter($values, static fn($value, $field) => $value !== $current[$field], ARRAY_FILTER_USE_BOTH);
        if ($changed && !$this->database->table('awt_admin')->where(['id' => $id])->update($changed)) {
            throw new InvalidArgumentException('The account changed. Reload and try again.');
        }
    }

    public function remove(int $id): void
    {
        $this->find($id);
        if (!$this->database->table('awt_admin')->where(['id' => $id])->delete()) {
            throw new InvalidArgumentException('The account changed. Reload and try again.');
        }
    }

    public function profileValues(array $input, ?int $id = null): array
    {
        $values = [];
        foreach (self::PROFILE_FIELDS as $field) {
            if (!isset($input[$field]) || !is_string($input[$field])) {
                throw new InvalidArgumentException('Provide all profile fields.');
            }
            $value = trim($input[$field]);
            if ($value === '' || strlen($value) > 255) {
                throw new InvalidArgumentException('Profile fields are required and must be at most 255 characters.');
            }
            $values[$field] = $value;
        }
        if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Enter a valid email address.');
        }
        foreach (['username', 'email'] as $field) {
            $duplicates = $this->database->table('awt_admin')->select(['id'])->where([$field => $values[$field]])->get();
            foreach ($duplicates as $duplicate) {
                if ((int)$duplicate['id'] !== $id) {
                    throw new InvalidArgumentException(ucfirst($field) . ' is already in use.');
                }
            }
        }
        return $values;
    }

    public function permission(mixed $level): int
    {
        if (!is_string($level) || !in_array($level, ['0', '1', '2'], true)) {
            throw new InvalidArgumentException('Choose a valid permission level.');
        }
        return (int)$level;
    }

    public function password(array $input): string
    {
        $new = $input['new_password'] ?? null;
        $confirmation = $input['password_confirmation'] ?? null;
        if (!is_string($new) || $new === '' || strlen($new) > 72) {
            throw new InvalidArgumentException('Enter a new password of at most 72 bytes.');
        }
        if (!is_string($confirmation) || $new !== $confirmation) {
            throw new InvalidArgumentException('The new password and confirmation do not match.');
        }
        return $new;
    }

    private function find(int $id): array
    {
        $rows = $this->database->table('awt_admin')->select()->where(['id' => $id])->get();
        if (!$rows) {
            throw new InvalidArgumentException('This account could not be found.');
        }
        return $rows[0];
    }
}
