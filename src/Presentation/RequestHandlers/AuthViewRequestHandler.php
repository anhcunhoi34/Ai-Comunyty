<?php

declare(strict_types=1);

namespace Presentation\RequestHandlers;

use Easy\Http\Message\RequestMethod;
use Easy\Router\Attributes\Middleware;
use Easy\Router\Attributes\Route;
use Presentation\Middlewares\ViewMiddleware;
use Presentation\Response\RedirectResponse;
use Presentation\Response\ViewResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use User\Domain\Entities\UserEntity;
use User\Domain\ValueObjects\Role;

/** @package Presentation\RequestHandlers\Auth */
#[Middleware(ViewMiddleware::class)]
#[Route(path: '/[login|signup|recovery:view]', method: RequestMethod::GET)]
class AuthViewRequestHandler extends AbstractRequestHandler implements
    RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserEntity::class);
        if ($user) {
            return new RedirectResponse(
                $user->getRole() == Role::ADMIN ? '/admin' : '/app'
            );
        }

        $view = $request->getAttribute('view');

        $data = [
            'demo_account_email' => env('DEMO_ACCOUNT_EMAIL'),
            'demo_account_password' => env('DEMO_ACCOUNT_PASSWORD'),
        ];

        return new ViewResponse(
            '/templates/auth/' . $view . '.twig',
            $data
        );
    }
}
