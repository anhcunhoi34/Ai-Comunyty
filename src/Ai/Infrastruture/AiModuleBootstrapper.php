<?php

declare(strict_types=1);

namespace Ai\Infrastruture;

use Ai\Domain\Services\AiServiceFactoryInterface;
use Ai\Infrastruture\Services\AiServiceFactory;
use Ai\Infrastruture\Services\OpenAi as OpenAiServices;
use Application;
use Easy\Container\Attributes\Inject;
use Http\Discovery\Exception\NotFoundException;
use OpenAI;
use OpenAI\Client;
use Psr\Http\Client\ClientInterface;
use Shared\Infrastructure\BootstrapperInterface;

/** @package Ai\Infrastruture */
class AiModuleBootstrapper implements BootstrapperInterface
{
    /**
     * @param Application $app 
     * @return void 
     */
    public function __construct(
        private Application $app,
        private AiServiceFactory $factory,
        private ClientInterface $httpClient,

        #[Inject('option.openai.api_secret_key')]
        private ?string $openAiApiKey = null
    ) {
    }

    /** @inheritDoc */
    public function bootstrap(): void
    {
        $this->setupAiServiceFactory();
        $this->setupOpenAIClient();
    }

    /** @return void  */
    private function setupAiServiceFactory(): void
    {
        $this->app->set(
            AiServiceFactoryInterface::class,
            $this->factory
        );

        $this->factory
            ->register(OpenAiServices\TextGeneratorService::class)
            ->register(OpenAiServices\TitleGeneratorService::class)
            ->register(OpenAiServices\CodeGeneratorService::class)
            ->register(OpenAiServices\ImageGeneratorService::class)
            ->register(OpenAiServices\SpeechToTextService::class);
    }

    /**
     * @return void 
     * @throws NotFoundException 
     */
    private function setupOpenAIClient(): void
    {
        if (!$this->openAiApiKey) {
            return;
        }

        $client = OpenAI::factory()
            ->withApiKey($this->openAiApiKey)
            ->withHttpClient($this->httpClient)
            ->make();

        $this->app->set(Client::class, $client);
    }
}
