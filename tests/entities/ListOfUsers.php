<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

use gugglegum\AbstractEntity\ArrayableInterface;

class ListOfUsers implements ArrayableInterface
{
    /** @var User[] */
    protected array $users = [];

    public function addUser(User $user): static
    {
        $this->users[] = $user;
        return $this;
    }

    public function toArray(?callable $objectToArrayHandler = null): array
    {
        $data = [];
        foreach ($this->users as $user) {
            $data[] = $user->toArray();
        }
        return $data;
    }

    /**
     * @param User[] $data
     * @return static
     */
    public function setFromArray(array $data): static
    {
        $this->users = [];
        foreach ($data as $user) {
            $this->addUser($user);
        }
        return $this;
    }

    public static function fromArray(array $data): static
    {
        return (new static())->setFromArray($data);
    }
}
