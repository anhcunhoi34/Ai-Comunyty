<?php

declare(strict_types=1);

namespace Ai\Infrastruture\Services\OpenAi;

use Ai\Domain\ValueObjects\Token;
use Ai\Domain\Exceptions\ModelNotSupportedException;
use Ai\Domain\Services\TextGeneratorServiceInterface;
use Billing\Domain\ValueObjects\Usage;
use Billing\Domain\ValueObjects\UsageType;
use Generator;
use Gioni06\Gpt3Tokenizer\Gpt3Tokenizer;
use OpenAI\Client;
use OpenAI\Exceptions\ErrorException;
use RuntimeException;

/** @package Ai\Infrastruture\Services\OpenAi */
class TextGeneratorService implements TextGeneratorServiceInterface
{
    private array $models = [
        'gpt-3.5-turbo',
        'gpt-3.5-turbo-16k',
        'gpt-4',
        // 'gpt-4-32k', # Model is not available yet
    ];

    /**
     * @param Client $client 
     * @return void 
     */
    public function __construct(
        private Client $client,
        private Gpt3Tokenizer $tokenizer
    ) {
    }

    /** @inheritDoc */
    public function supportsModel(string $model): bool
    {
        return in_array($model, $this->models);
    }

    /**
     * @inheritDoc
     * @throws ErrorException 
     * @throws RuntimeException 
     */
    public function generateText(
        string $prompt,
        array $params = [],
        ?string $model = null
    ): Generator {
        $model = $model ?: $this->models[0];

        if (!$this->supportsModel($model)) {
            throw new ModelNotSupportedException(
                self::class,
                $model
            );
        }

        $resp = $this->client->chat()->createStreamed([
            'model' => $model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'n' => 1,
            'temperature' => (int)($params['temperature'] ?? 1),
        ]);

        yield new Usage(
            UsageType::TOKEN,
            $this->tokenizer->count($prompt)
        );

        foreach ($resp as $item) {
            $content = $item->choices[0]->delta->content;

            if ($content) {
                yield new Token($content);
            }

            yield new Usage(
                UsageType::TOKEN,
                1
            );
        }
    }
}
