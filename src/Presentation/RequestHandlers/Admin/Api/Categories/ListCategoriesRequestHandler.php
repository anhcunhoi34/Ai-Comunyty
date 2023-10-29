<?php

declare(strict_types=1);

namespace Presentation\RequestHandlers\Admin\Api\Categories;

use Category\Application\Commands\ListCategoriesCommand;
use Category\Domain\Entities\CategoryEntity;
use Presentation\Resources\Admin\Api\CategoryResource;
use Easy\Http\Message\RequestMethod;
use Easy\Router\Attributes\Route;
use Iterator;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Infrastructure\CommandBus\Dispatcher;
use Shared\Infrastructure\CommandBus\Exception\NoHandlerFoundException;
use Presentation\Response\JsonResponse;
use Presentation\Resources\ListResource;

#[Route(path: '/', method: RequestMethod::GET)]
class ListCategoriesRequestHandler extends CategoryApi
implements RequestHandlerInterface
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
     * @throws NoHandlerFoundException 
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = (object) $request->getQueryParams();

        $cmd = new ListCategoriesCommand();

        if ($params->sort ?? null) {
            $sort = explode(':', $params->sort);
            $orderBy = $sort[0];
            $dir = $sort[1] ?? 'desc';
            $cmd->setOrderBy($orderBy, $dir);
        }

        if ($params->starting_after ?? null) {
            $cmd->setCursor(
                $params->starting_after,
                'starting_after'
            );
        } elseif ($params->ending_before ?? null) {
            $cmd->setCursor(
                $params->ending_before,
                'ending_before'
            );
        }

        if ($params->limit ?? null) {
            $cmd->setLimit((int) $params->limit);
        }

        /** @var Iterator<int,CategoryEntity> $presets */
        $presets = $this->dispatcher->dispatch($cmd);
        $res = new ListResource();

        foreach ($presets as $preset) {
            $res->pushData(new CategoryResource($preset));
        }

        return new JsonResponse($res);
    }
}
