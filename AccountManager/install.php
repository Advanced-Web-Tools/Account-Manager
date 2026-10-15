<?php

use installer\interfaces\package\actions\IPostInstall;

class AccountManagerInstall implements IPostInstall
{
    public function postInstall(int $packageId, string $packageName): bool
    {
        return true;
    }
}
