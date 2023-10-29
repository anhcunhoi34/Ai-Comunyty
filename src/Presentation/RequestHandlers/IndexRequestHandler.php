<?php

declare(strict_types=1);

namespace Presentation\RequestHandlers;

use Billing\Application\Commands\ListPlansCommand;
use Easy\Http\Message\RequestMethod;
use Easy\Router\Attributes\Middleware;
use Easy\Router\Attributes\Route;
use Presentation\Middlewares\ViewMiddleware;
use Presentation\Resources\PlanResource;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Presentation\Response\ViewResponse;
use Shared\Infrastructure\CommandBus\Dispatcher;

/** @package Presentation\RequestHandlers */
#[Middleware(ViewMiddleware::class)]
#[Route(path: '/', method: RequestMethod::GET)]
class IndexRequestHandler extends AbstractRequestHandler implements
    RequestHandlerInterface
{
    /**
     * @param Dispatcher $dispatcher 
     * @return void 
     */
    public function __construct(
        private Dispatcher $dispatcher
    ) {
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(
        ServerRequestInterface $request
    ): ResponseInterface {
        $cmd = new ListPlansCommand();
        $cmd->setStatus(1);
        $cmd->setOrderBy('price', 'ASC');
        $plans = $this->dispatcher->dispatch($cmd);

        $resources = [];
        foreach ($plans as $plan) {
            $resources[] = new PlanResource($plan);
        };

        return new ViewResponse(
            '@theme/templates/index.twig',
            ['plans' =>  $resources]
        );
    }
}
