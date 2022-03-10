<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

use gugglegum\AbstractEntity\ArrayableInterface;

class GroupsOfUsers implements ArrayableInterface
{
    /** @var array<string,User[]> */
    private array $groupedUsers = [];

    public function addUserToGroup(User $user, string $group): static
    {
        $this->groupedUsers[$group][] = $user;
        return $this;
    }

    public function toArray(?callable $objectToArrayHandler = null): array
    {
        $data = [];
        foreach ($this->groupedUsers as $group => $users) {
            foreach ($users as $user) {
                $data[$group][] = $user->toArray();
            }
        }
        return $data;
    }

    /**
     * @param array<string,User[]> $data
     * @return static
     */
    public function setFromArray(array $data): static
    {
        $this->groupedUsers = [];
        foreach ($data as $group => $users) {
            foreach ($users as $user) {
                $this->addUserToGroup($user, $group);
            }
        }
        return $this;
    }

    public static function fromArray(array $data): static
    {
        return (new static())->setFromArray($data);
    }
}
