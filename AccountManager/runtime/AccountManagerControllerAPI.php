<?php

use controller\Controller;
use object\ObjectFactory;
use runtime\api\RuntimeControllerAPI;
use runtime\enums\ERuntimeFlags;

final class AccountManagerControllerAPI extends RuntimeControllerAPI
{
    private ObjectFactory $factory;

    public function environmentSetup(): void
    {
        parent::environmentSetup();
        $this->setRuntimeFlag(ERuntimeFlags::EventDispatcher);
    }

    public function setup(): void
    {
        $this->factory = (new ObjectFactory())
            ->setType(Controller::class)
            ->setClassPath($this->runtimePath . '/controllers/AccountManagerController.php')
            ->setProperties([
                'controllerName' => 'AccountManagerController',
                'localAssetPath' => 'awt_packages/Dashboard/views/assets',
                'eventDispatcher' => $this->eventDispatcher,
            ])
            ->setMethodCalls(['setRootPath', 'setViewPath'])
            ->setMethodArgs([
                'setRootPath' => [$this->runtimePath],
                'setViewPath' => ['/views/'],
            ]);
    }

    public function main(): void
    {
        $this->addController($this->factory, 'AccountManagerController');
    }
}
