<?php

declare(strict_types=1);

namespace Presentation\RequestHandlers\App;

use Easy\Http\Message\RequestMethod;
use Easy\Router\Attributes\Route;
use Presentation\Response\ViewResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[Route(path: '/account/[profile|email|password:name]?', method: RequestMethod::GET)]
class AccountViewRequestHandler extends AppView implements
    RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getAttribute('name');

        if (!$name) {
            $name = 'profile';
        }

        return new ViewResponse(
            '/templates/app/account/' . $name . '.twig'
        );
    }
}
