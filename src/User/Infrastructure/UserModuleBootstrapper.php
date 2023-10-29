<?php

declare(strict_types=1);

namespace User\Infrastructure;

use Application;
use Shared\Infrastructure\BootstrapperInterface;
use User\Domain\Repositories\UserRepositoryInterface;
use User\Infrastructure\Repositories\DoctrineOrm\UserRepository;

/** @package User\Infrastructure */
class UserModuleBootstrapper implements BootstrapperInterface
{
    /**
     * @param Application $app
     * @return void
     */
    public function __construct(
        private Application $app
    ) {
    }

    /** @return void  */
    public function bootstrap(): void
    {
        // Register repository implementations
        $this->app->set(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }
}
