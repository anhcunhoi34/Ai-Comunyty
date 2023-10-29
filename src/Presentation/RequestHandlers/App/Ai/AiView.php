<?php

declare(strict_types=1);

namespace Presentation\RequestHandlers\App\Ai;

use Easy\Router\Attributes\Path;
use Presentation\RequestHandlers\App\AppView;

#[Path('/ai')]
abstract class AiView extends AppView
{
}
