<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

use DateTime;
use gugglegum\AbstractEntity\AbstractEntity;

/**
 * Message
 *
 * A base class for Post and CustomPost. Used mainly to test correct work with inherited entities.
 */
class Message extends AbstractEntity
{
    /**
     * @var DateTime
     */
    private DateTime $datetime;

    /**
     * @var int
     */
    private int $userId;

    /**
     * @var string
     */
    private string $text;

    /**
     * @return DateTime
     */
    public function getDatetime(): DateTime
    {
        return $this->datetime;
    }

    /**
     * @param DateTime $datetime
     * @return self
     */
    public function setDatetime(DateTime $datetime): self
    {
        $this->datetime = $datetime;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return self
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return self
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }
}
