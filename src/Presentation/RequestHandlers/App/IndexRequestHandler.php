<?php

declare(strict_types=1);

namespace Presentation\RequestHandlers\App;

use Easy\Http\Message\RequestMethod;
use Easy\Router\Attributes\Middleware;
use Easy\Router\Attributes\Route;
use Presentation\Middlewares\AuthorizationMiddleware;
use Presentation\Middlewares\ViewMiddleware;
use Presentation\RequestHandlers\AbstractRequestHandler;
use Presentation\Response\ViewResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[Middleware(AuthorizationMiddleware::class)]
#[Middleware(ViewMiddleware::class)]
#[Route(path: '/app', method: RequestMethod::GET)]
class IndexRequestHandler extends AbstractRequestHandler implements
    RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new ViewResponse(
            '/templates/app/app.twig'
        );
    }
}
