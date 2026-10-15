<?php

namespace AccountManager\service;

use admin\Admin;
use admin\AdminPassword;
use database\DatabaseManager;
use database\trait\DoNotCache;
use InvalidArgumentException;

final class Accounts
{
    private DatabaseManager $database;
    private AccountRecords $records;
    public const PLACEHOLDER = '/awt_packages/AccountManager/views/assets/images/profile_placeholder.webp';
    private const FIELDS = ['id', 'firstname', 'lastname', 'username', 'email', 'profile_picture', 'permission_level'];

    public function __construct(?DatabaseManager $database = null, private readonly ?ProfilePictures $pictures = null)
    {
        $this->database = $database ?? new class extends DatabaseManager { use DoNotCache; };
        $this->records = new AccountRecords($this->database);
    }

    public function current(Admin $actor): array
    {
        if (!$actor->checkAuthentication() || !$actor->getKey()) {
            throw new InvalidArgumentException('Please sign in to manage your account.');
        }
        return $this->find((int)$actor->getKey());
    }

    public function find(int $id): array
    {
        $rows = $this->database->table('awt_admin')->select(self::FIELDS)->where(['id' => $id])->get();
        if (!$rows) {
            throw new InvalidArgumentException('This account could not be found.');
        }
        return $rows[0];
    }

    public function directory(Admin $actor): array
    {
        $this->current($actor);
        return $this->database->table('awt_admin')->select(self::FIELDS)->orderBy('firstname')->orderBy('lastname')->get();
    }

    public function profile(Admin $actor, int $id): array
    {
        $this->current($actor);
        return $this->find($id);
    }

    public function target(Admin $actor, int $id): array
    {
        $current = $this->current($actor);
        if ((int)$current['id'] !== $id && (int)$current['permission_level'] !== 0) {
            throw new InvalidArgumentException('You do not have permission to edit this account.');
        }
        return $this->find($id);
    }

    public static function picture(mixed $value): string
    {
        if (!is_string($value) || $value === '') {
            return self::PLACEHOLDER;
        }
        if (preg_match('~^/(?!/)[^\x00-\x20\\\\]*$~D', $value)) {
            return $value;
        }
        return filter_var($value, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($value, PHP_URL_SCHEME) ?? ''), ['https', 'http'], true) ? $value : self::PLACEHOLDER;
    }

    public function save(Admin $actor, int $id, array $input, ?array $upload = null): void
    {
        $current = $this->current($actor);
        $target = $this->target($actor, $id);
        $values = $this->records->profileValues($input, $id);
        if (array_key_exists('permission_level', $input)) {
            if ((int)$current['permission_level'] !== 0) {
                throw new InvalidArgumentException('Only administrators can change permissions.');
            }
            $values['permission_level'] = $this->records->permission($input['permission_level']);
        }
        $pictures = $this->pictures ?? new ProfilePictures();
        $entry = null;
        if ($upload !== null && ($upload['error'] ?? null) !== UPLOAD_ERR_NO_FILE) {
            $entry = $pictures->upload($upload, $id);
            $values['profile_picture'] = $entry->getUrl();
        }
        try {
            $changed = array_filter($values, static fn($value, $field) => $value !== $target[$field], ARRAY_FILTER_USE_BOTH);
            if ($changed && !$this->database->table('awt_admin')->where(['id' => $id])->update($changed)) {
                throw new InvalidArgumentException('The account changed. Reload and try again.');
            }
        } catch (\Throwable $exception) {
            if ($entry !== null) {
                $pictures->discard($entry);
            }
            throw $exception;
        }
    }

    public function requireManager(Admin $actor): array
    {
        $current = $this->current($actor);
        if ((int)$current['permission_level'] !== 0) {
            throw new InvalidArgumentException('Only administrators can manage accounts.');
        }
        return $current;
    }

    public function create(Admin $actor, array $input): int
    {
        $this->requireManager($actor);
        return $this->records->create($input);
    }

    public function delete(Admin $actor, int $id): void
    {
        $current = $this->requireManager($actor);
        if ((int)$current['id'] === $id) {
            throw new InvalidArgumentException('You cannot delete your own account.');
        }
        $this->records->remove($id);
    }

    public function password(Admin $actor, int $id, array $input): void
    {
        $this->target($actor, $id);
        $new = $this->records->password($input);
        $rows = $this->database->table('awt_admin')->select(['password'])->where(['id' => $id])->get();
        if (!$rows) {
            throw new InvalidArgumentException('This account could not be found.');
        }
        $hash = $rows[0]['password'];
        if ($id === (int)$actor->getKey()) {
            $old = $input['current_password'] ?? null;
            if (!is_string($old) || !AdminPassword::verify($old, $hash)) {
                throw new InvalidArgumentException('Current password is incorrect.');
            }
        }
        if (!$this->database->table('awt_admin')->where(['id' => $id, 'password' => $hash])->update(['password' => AdminPassword::hash($new)])) {
            throw new InvalidArgumentException('The account changed. Reload and try again.');
        }
    }
}
