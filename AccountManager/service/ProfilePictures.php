<?php

namespace AccountManager\service;

use InvalidArgumentException;
use vfs\storage\enums\EOwnerType;
use vfs\storage\interfaces\IStorageFacade;
use vfs\storage\Storage;
use vfs\storage\StorageEntry;
use vfs\storage\StorageManager;
use vfs\storage\StorageRepository;
use vfs\storage\services\LocalFileSystemService;

final class ProfilePictures
{
    private const MAX_BYTES = 5 * 1024 * 1024;
    private const TYPES = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];

    public function __construct(private readonly ?IStorageFacade $storage = null) {}

    public function upload(array $file, int $accountId): StorageEntry
    {
        if (($file['error'] ?? null) !== UPLOAD_ERR_OK) {
            throw new InvalidArgumentException('Profile picture upload failed. Choose an image up to 5 MB and try again.');
        }
        $path = $file['tmp_name'] ?? null;
        if (!is_string($path) || !is_uploaded_file($path)) {
            throw new InvalidArgumentException('Choose a valid profile picture upload.');
        }
        $size = filesize($path);
        if ($size === false || $size < 1 || $size > self::MAX_BYTES) {
            throw new InvalidArgumentException('Profile pictures must be no larger than 5 MB.');
        }
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($path);
        $image = @getimagesize($path);
        if (!isset(self::TYPES[$mime]) || !$image || ($image['mime'] ?? '') !== $mime) {
            throw new InvalidArgumentException('Choose a JPEG, PNG, GIF or WebP image.');
        }
        // Use a server-selected extension; never publish an uploaded executable filename.
        $file['name'] = 'profile-' . $accountId . '.' . self::TYPES[$mime];
        return ($this->storage ?? new Storage())->upload($file, EOwnerType::PACKAGE, ownerName: 'AccountManager');
    }

    public function discard(StorageEntry $entry): void
    {
        if ($entry->getId() !== null) {
            $manager = new StorageManager(new StorageRepository(), new LocalFileSystemService());
            if (!$manager->delete($entry->getId())) {
                throw new \RuntimeException('Unable to remove an unused profile picture.');
            }
        }
    }
}
