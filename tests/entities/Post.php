<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

/**
 * Post
 *
 * A simple entity for post in a blog. Just an example in tests.
 */
class Post extends Message
{
    /** @var string */
    private string $title;

    /** @var string[] */
    private array $labels = [];

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * @param string[] $labels
     * @return self
     */
    public function setLabels(array $labels): self
    {
        $this->labels = $labels;
        return $this;
    }
}
