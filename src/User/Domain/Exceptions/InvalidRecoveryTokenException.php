<?php

declare(strict_types=1);

namespace User\Domain\Exceptions;

use Exception;
use Throwable;
use User\Domain\Entities\UserEntity;
use User\Domain\ValueObjects\RecoveryToken;

/** 
 * Class InvalidRecoveryTokenException extends the base Exception.
 *
 * This Exception class is thrown when there are issues related to recovery 
 * token validation. It contains details about the user and the token that 
 * caused the exception.
 *
 * @package User\Domain\Exceptions 
 */
class InvalidRecoveryTokenException extends Exception
{
    /**
     * Constructs a new instance of InvalidRecoveryTokenException.
     *
     * @param UserEntity $user The associated user entity that caused this 
     * exception.
     * @param RecoveryToken $token The recovery token causing the exception.
     * @param int $code The Exception code.
     * @param Throwable|null $previous The previous throwable used for exception 
     * chaining.
     */
    public function __construct(
        public readonly UserEntity $user,
        public readonly RecoveryToken $token,
        int $code = 0,
        Throwable $previous = null
    ) {
        // Calls the parent constructor with a message, code and previous 
        // throwable (if any)
        parent::__construct(
            "Recovery token is incorrect for user!",
            $code,
            $previous
        );
    }
}
