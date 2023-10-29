<?php

declare(strict_types=1);

namespace Ai\Domain\Services;

use Ai\Domain\Exceptions\ModelNotSupportedException;
use Ai\Domain\ValueObjects\Image;
use Billing\Domain\ValueObjects\Usage;
use Generator;

interface ImageGeneratorServiceInterface extends AiServiceInterface
{
    /**
     * @param string $prompt 
     * @param int $size 
     * @param array $params 
     * @param null|string $model 
     * @return Generator<int,Image|Usage> 
     * @throws ModelNotSupportedException
     */
    public function generate(
        string $prompt,
        int $size = 1024,
        array $params = [],
        ?string $model = null,
    ): Generator;
}
