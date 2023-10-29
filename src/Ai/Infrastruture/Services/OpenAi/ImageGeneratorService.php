<?php

declare(strict_types=1);

namespace Ai\Infrastruture\Services\OpenAi;

use Ai\Domain\Exceptions\ModelNotSupportedException;
use Ai\Domain\Services\ImageGeneratorServiceInterface;
use Ai\Domain\ValueObjects\Image;
use Billing\Domain\ValueObjects\Usage;
use Billing\Domain\ValueObjects\UsageType;
use Generator;
use InvalidArgumentException;
use OpenAI\Client;
use OpenAI\Exceptions\ErrorException;
use OpenAI\Exceptions\UnserializableResponse;
use OpenAI\Exceptions\TransporterException;
use Psr\Http\Message\UriFactoryInterface;

class ImageGeneratorService implements ImageGeneratorServiceInterface
{
    private array $models = [
        'dall-e'
    ];

    /**
     * @param Client $client 
     * @param UriFactoryInterface $factory 
     * @return void 
     */
    public function __construct(
        private Client $client,
        private UriFactoryInterface $factory
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
     * @throws UnserializableResponse 
     * @throws TransporterException 
     * @throws InvalidArgumentException 
     */
    public function generate(
        string $prompt,
        int $size = 1024,
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

        if (!in_array($size, [256, 512, 1024])) {
            $size = 1024;
        }

        try {
            $resp = $this->client->images()->create([
                'prompt' => $prompt,
                'size' => $size . 'x' . $size,
                'response_format' => 'b64_json',
                'n' => 1
            ]);
        } catch (ErrorException $th) {
            yield $th;
            return;
        }

        $count = 0;
        foreach ($resp->data as $item) {
            yield new Image(
                'data:image/png;base64,' . $item->b64_json
            );

            $count++;
        }

        yield new Usage(
            UsageType::IMAGE,
            $count
        );
    }
}
