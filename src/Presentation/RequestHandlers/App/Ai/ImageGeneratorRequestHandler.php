<?php

declare(strict_types=1);

namespace Presentation\RequestHandlers\App\Ai;

use Easy\Http\Message\RequestMethod;
use Easy\Router\Attributes\Route;
use Presentation\Response\ViewResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Infrastructure\CommandBus\Dispatcher;

/** @package Presentation\RequestHandlers */
#[Route(path: '/image-generator', method: RequestMethod::GET)]
class ImageGeneratorRequestHandler extends AiView implements
    RequestHandlerInterface
{
    public function __construct(
        private Dispatcher $dispatcher
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new ViewResponse(
            '/templates/app/ai/image-generator.twig',
        );
    }
}
