<?php

namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;
use User\Model\Service\UserModelService;
use User\Service\AuthService;

class AuthServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthService
    {
        $userModelService = $container->get(UserModelService::class);
        $authenticationService = $container->get(AuthenticationService::class);
        $dbAdapter = $container->get(Adapter::class);

        return new AuthService($userModelService, $authenticationService, $dbAdapter);
    }
}