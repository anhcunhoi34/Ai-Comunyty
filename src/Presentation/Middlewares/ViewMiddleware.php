<?php

declare(strict_types=1);

namespace Presentation\Middlewares;

use Easy\Container\Attributes\Inject;
use Option\Infrastructure\OptionResolver;
use Presentation\Resources\Api\SubscriptionResource;
use Presentation\Resources\CurrencyResource;
use Presentation\Resources\UserResource;
use Presentation\Response\ViewResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use User\Domain\Entities\UserEntity;

/** @package Presentation\Middlewares */
class ViewMiddleware implements MiddlewareInterface
{
    /**
     * @param Environment $twig 
     * @param StreamFactoryInterface $streamFactory 
     * @return void 
     */
    public function __construct(
        private Environment $twig,
        private StreamFactoryInterface $streamFactory,
        private OptionResolver $optionResolver,

        #[Inject('version')]
        private string $version,
    ) {
    }

    /** @inheritDoc */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $resp = $handler->handle($request);

        if (!($resp instanceof ViewResponse)) {
            return $resp;
        }

        $data = $resp->getData();
        $data = array_merge($data, $this->optionResolver->getOptionMap());
        $data['version'] = $this->version;
        $data['environment'] = env('ENVIRONMENT');

        $data['view_namespace'] = $this->getViewNamespace($request);
        $data['currency'] = new CurrencyResource(
            $this->optionResolver->resolve('option.billing.currency') ?: 'USD'
        );

        $isLocked = $this->isLocked($request);
        $user = $request->getAttribute(UserEntity::class);

        if ($user) {
            $data['auth_user'] = new UserResource($user);

            $packs = [];
            foreach ($user->getTokenPacks() as $pack) {
                $packs[] = new SubscriptionResource($pack);
            }

            if ($isLocked) {
                $data['subscription'] = $user->getActiveSubscription()
                    ? new SubscriptionResource($user->getActiveSubscription())
                    : null;

                $data['packs'] = $packs;
            }
        }

        $stream = $this->streamFactory->createStream();
        $stream->write(
            $this->twig->render(
                $this->isLocked($request) ? 'templates/app/billing/locked.twig' : $resp->getTemplate(),
                $data
            )
        );

        return $resp->withBody($stream);
    }

    /**
     * @param ServerRequestInterface $request 
     * @return null|string 
     */
    private function getViewNamespace(ServerRequestInterface $request): ?string
    {
        $path = $request->getUri()->getPath();

        $prefixes = ['admin', 'app'];

        foreach ($prefixes as $prefix) {
            if (strpos($path, "/{$prefix}") !== false) {
                return $prefix;
            }
        }

        return null;
    }

    private function isLocked(ServerRequestInterface $request): bool
    {
        /** @var null|UserEntity */
        $user = $request->getAttribute(UserEntity::class);
        if (!$user) {
            return false;
        }

        $path = $request->getUri()->getPath();

        if (
            strpos($path, '/text-generator') !== false
            && !is_null($user->getTokenCredit()->value)
            && $user->getTokenCredit()->value <= 0
        ) {
            return true;
        }

        if (
            strpos($path, '/code-generator') !== false
            && !is_null($user->getTokenCredit()->value)
            && $user->getTokenCredit()->value <= 0
        ) {
            return true;
        }

        if (
            strpos($path, '/image-generator') !== false
            && !is_null($user->getImageCredit()->value)
            && $user->getImageCredit()->value <= 0
        ) {
            return true;
        }

        if (
            strpos($path, '/speech-to-text') !== false
            && !is_null($user->getAudioCredit()->value)
            && $user->getAudioCredit()->value <= 0
        ) {
            return true;
        }

        return false;
    }
}
