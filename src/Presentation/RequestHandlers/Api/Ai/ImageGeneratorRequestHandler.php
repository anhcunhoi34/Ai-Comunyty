<?php

declare(strict_types=1);

namespace Presentation\RequestHandlers\Api\Ai;

use Ai\Domain\Services\AiServiceFactoryInterface;
use Ai\Domain\Services\ImageGeneratorServiceInterface;
use Billing\Application\Commands\UseCreditCommand;
use Easy\Http\Message\RequestMethod;
use Easy\Http\Message\StatusCode;
use Easy\Router\Attributes\Route;
use Presentation\EventStream\Streamer;
use Presentation\Http\Message\CallbackStream;
use Presentation\Response\EmptyResponse;
use Presentation\Response\Response;
use Preset\Application\Commands\ReadPresetCommand;
use Preset\Domain\Exceptions\PresetNotFoundException;
use Preset\Domain\Placeholder\ParserService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Infrastructure\CommandBus\Dispatcher;
use User\Domain\Entities\UserEntity;

#[Route(path: '/image-generator/[uuid:id]?', method: RequestMethod::GET)]
class ImageGeneratorRequestHandler extends AiServicesApi implements
    RequestHandlerInterface
{
    public function __construct(
        private Dispatcher $dispatcher,
        private ParserService $parser,
        private AiServiceFactoryInterface $factory,
        private Streamer $streamer
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $prompt = $this->getPrompt($request);
        } catch (PresetNotFoundException $th) {
            return new EmptyResponse(StatusCode::NOT_FOUND);
        }

        $resp = (new Response())
            ->withHeader('Content-Type', 'text/event-stream')
            ->withHeader('Cache-Control', 'no-cache');

        $stream = new CallbackStream(
            $this->callback(...),
            $request,
            $prompt
        );

        return $resp->withBody($stream);
    }

    private function callback(ServerRequestInterface $request, string $prompt)
    {
        /** @var UserEntity */
        $user = $request->getAttribute(UserEntity::class);

        $params = $request->getQueryParams();
        $model = $params['model'] ?? null;

        $service = $this->factory->create(
            ImageGeneratorServiceInterface::class,
            $model
        );

        // $this->streamer->open();

        $count = (int) ($params['n'] ?? 1);
        for ($i = 0; $i < $count; $i++) {
            $result = $service->generate(
                $prompt,
                (int) ($params['size'] ?? 1024),
                $params,
                $model
            );

            $usages = $this->streamer->stream($result);

            $cmd = new UseCreditCommand($user, ...$usages);
            $this->dispatcher->dispatch($cmd);
        }

        $this->streamer->close();
    }

    private function getPrompt(ServerRequestInterface $request): string
    {
        $id = $request->getAttribute('id');
        $params = $request->getQueryParams();

        $prompt = $params['prompt'] ?? '';

        if ($id) {
            $cmd = new ReadPresetCommand($id);

            /** @var PresetEntity $preset */
            $preset = $this->dispatcher->dispatch($cmd);

            $prompt = $this->parser->fillTemplate(
                $preset->getTemplate()->value,
                $params
            );
        }

        $instructions = [];
        if (isset($params['art'])) {
            $instructions[] = '- Art style: ' . $params['art'];
        }

        if (isset($params['lightning'])) {
            $instructions[] = '- Lightning: ' . $params['lightning'];
        }

        if (isset($params['mood'])) {
            $instructions[] = '- Mood: ' . $params['mood'];
        }

        if ($instructions) {
            $prompt .= "\n\nAdditional instructions:\n" . implode("\n", $instructions);
        }

        return $prompt;
    }
}
