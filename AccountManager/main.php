<?php

use AccountManager\cli\CreateAccountCommand;
use AccountManager\cli\ListAccountsCommand;
use AccountManager\cli\ModifyAccountCommand;
use AccountManager\cli\RemoveAccountCommand;
use Dashboard\api\events\NavigationResponseEvent;
use Dashboard\api\navigation\NavigationItem;
use runtime\api\RuntimeLinkerAPI;
use runtime\enums\ERuntimeFlags;

final class AccountManager extends RuntimeLinkerAPI
{
    public function environmentSetup(): void
    {
        parent::environmentSetup();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
        $this->setRuntimeFlag(ERuntimeFlags::CommandProvider);
    }

    public function main(): void
    {
        $this->addCommand(new CreateAccountCommand());
        $this->addCommand(new RemoveAccountCommand());
        $this->addCommand(new ModifyAccountCommand());
        $this->addCommand(new ListAccountsCommand());

        $this->createLink("AccountManagerControllerAPI", "/runtime/AccountManagerControllerAPI.php");
        $this->createLink("AccountManagerRouter", "/runtime/AccountManagerRouter.php");

        $nav = new NavigationItem('Accounts', '/dashboard/accounts', '/awt_packages/AccountManager/data/icon/icon.webp');
        $nav->addChild(new NavigationItem('My Account', '/dashboard/accounts/me'));
        $nav->addChild(new NavigationItem('Accounts', '/dashboard/accounts'));
        $nav->addChild(new NavigationItem('Create account', '/dashboard/accounts/create', permissionLevel: 0));
        $nav->addChild(new NavigationItem('Help', '/dashboard/accounts/help'));
        $this->eventDispatcher->addListener(NavigationResponseEvent::NAME, new NavigationResponseEvent($nav));
    }
}
