<?php

declare(strict_types=1);

namespace Presentation\RequestHandlers\Api\Auth;

use Easy\Http\Message\RequestMethod;
use Easy\Router\Attributes\Route;
use Presentation\Response\Api\Auth\AuthResponse;
use Presentation\Exceptions\HttpException;
use Presentation\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Infrastructure\CommandBus\Dispatcher;
use User\Application\Commands\CreateUserCommand;
use User\Domain\Exceptions\EmailTakenException;

#[Route(path: '/signup', method: RequestMethod::POST)]
class SignupRequestHandler extends AuthApi implements
    RequestHandlerInterface
{
    public function __construct(
        private Validator $validator,
        private Dispatcher $dispatcher
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->validateRequest($request);
        $payload = (object) $request->getParsedBody();

        $cmd = new CreateUserCommand(
            $payload->email,
            $payload->password,
            $payload->first_name,
            $payload->last_name
        );

        try {
            $user = $this->dispatcher->dispatch($cmd);
        } catch (EmailTakenException $th) {
            throw new HttpException(
                message: $th->getMessage(),
                param: 'email'
            );
        }

        return new AuthResponse($user);
    }

    private function validateRequest(ServerRequestInterface $req): void
    {
        $this->validator->validateRequest($req, [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
    }
}
