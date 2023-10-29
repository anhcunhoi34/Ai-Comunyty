<?php

declare(strict_types=1);

namespace User\Application\Commands;

use Shared\Infrastructure\CommandBus\Attributes\Handler;
use User\Application\CommandHandlers\CreateUserCommandHandler;
use User\Domain\ValueObjects\Email;
use User\Domain\ValueObjects\FirstName;
use User\Domain\ValueObjects\Language;
use User\Domain\ValueObjects\LastName;
use User\Domain\ValueObjects\Password;
use User\Domain\ValueObjects\Role;
use User\Domain\ValueObjects\Status;

/** @package User\Application\Commands */
#[Handler(CreateUserCommandHandler::class)]
class CreateUserCommand
{
    public Email $email;
    public Password $password;
    public FirstName $firstName;
    public LastName $lastName;
    public ?Language $language = null;

    public ?Role $role = null;
    public ?Status $status = null;

    /**
     * @param string $email
     * @param string $password
     * @param string $firstName
     * @param string $lastName
     * @return void
     */
    public function __construct(
        string $email,
        string $password,
        string $firstName,
        string $lastName
    ) {
        $this->email = new Email($email);
        $this->password = new Password($password);
        $this->firstName = new FirstName($firstName);
        $this->lastName = new LastName($lastName);
    }

    /**
     * @param string $language
     * @return CreateUserCommand
     */
    public function setLanguage(string $language): self
    {
        $this->language = new Language($language);
        return $this;
    }

    /**
     * @param int $role
     * @return CreateUserCommand
     */
    public function setRole(int $role): self
    {
        $this->role = Role::from($role);
        return $this;
    }

    /**
     * @param int $status
     * @return CreateUserCommand
     */
    public function setStatus(int $status): self
    {
        $this->status = Status::from($status);
        return $this;
    }
}
