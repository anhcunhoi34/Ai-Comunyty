<?php

declare(strict_types=1);

namespace Ai\Domain\Services;

/** @package Ai\Domain\Services */
interface AiServiceFactoryInterface
{
    /**
     * Get/create a AI service for a given service class string.
     *
     * @template T of GeneratorInterface
     * @param class-string<T> $name 
     * @param null|string $model 
     * @return T
     */
    public function create(
        string $name,
        ?string $model = null
    ): AiServiceInterface;
}
