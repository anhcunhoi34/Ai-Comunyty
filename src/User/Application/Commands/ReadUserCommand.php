<?php

declare(strict_types=1);

namespace User\Application\Commands;

use Shared\Domain\ValueObjects\Id;
use Shared\Infrastructure\CommandBus\Attributes\Handler;
use User\Application\CommandHandlers\ReadUserCommandHandler;

/** @package User\Application\Commands */
#[Handler(ReadUserCommandHandler::class)]
class ReadUserCommand
{
    public Id $id;

    /**
     * @param string|Id $id 
     * @return void 
     */
    public function __construct(string|Id $id)
    {
        $this->id = is_string($id) ? new Id($id) : $id;
    }
}
