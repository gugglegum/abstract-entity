<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\models;

use gugglegum\AbstractEntity\AbstractEntity;

/**
 * Message
 *
 * A base class for Post and CustomPost. Used mainly to test correct work with inherited models.
 *
 * @package gugglegum\AbstractEntity\tests\models
 */
class Message extends AbstractEntity
{
    /**
     * @var \DateTime
     */
    private $datetime;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $text;

    /**
     * @return \DateTime|null
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param \DateTime $datetime
     * @return self
     */
    public function setDatetime(\DateTime $datetime): self
    {
        $this->datetime = $datetime;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getUserId()
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
     * @return string|null
     */
    public function getText()
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
