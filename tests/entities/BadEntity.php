<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

use gugglegum\AbstractEntity\AbstractEntity;

/**
 * Non-well formed entity class (missing getter and setter). This class used just to check error handling inside
 * AbstractEntity.
 */
class BadEntity extends AbstractEntity
{
    /**
     * This is normal attribute
     *
     * @var int|null
     */
    private $userId;

    /**
     * This attribute has only setter
     *
     * @var int|null
     */
    private $messageId;

    /**
     * This attribute has only getter
     *
     * @var int|null
     */
    private $topicId;

    /**
     * @var bool
     */
    private $isProduction = false;

    /**
     * @return int|null
     */
    public function getUserId(): ?int
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
     * @param int $messageId
     * @return self
     */
    public function setMessageId(int $messageId): self
    {
        $this->messageId = $messageId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTopicId(): ?int
    {
        return $this->topicId;
    }
}
