<?php

use runtime\api\RuntimeControllerAPI;
use runtime\api\RuntimeRouterAPI;
use router\Router;

final class AccountManagerRouter extends RuntimeRouterAPI
{
    private RuntimeControllerAPI $controllerApi;

    public function setup(): void
    {
        $this->controllerApi = $this->getPassable('AccountManager', 'AccountManagerControllerAPI');
    }

    public function main(): void
    {
        $controller = $this->controllerApi->getController('AccountManagerController');
        foreach ([
            ['/dashboard/accounts', 'index', 'GET'],
            ['/dashboard/accounts/me', 'mine', 'GET'],
            ['/dashboard/accounts/help', 'help', 'GET'],
            ['/dashboard/accounts/create', 'createForm', 'GET'],
            ['/dashboard/accounts/create', 'create', 'POST'],
            ['/dashboard/accounts/save', 'save', 'POST'],
            ['/dashboard/accounts/password', 'password', 'POST'],
            ['/dashboard/accounts/delete', 'delete', 'POST'],
            ['/dashboard/accounts/{account_id}/edit', 'edit', 'GET'],
            ['/dashboard/accounts/{account_id}', 'show', 'GET'],
        ] as [$path, $action, $method]) {
            $this->addRouter((new Router($path, $action, clone $controller))->setMethod($method));
        }
    }
}
