<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\models;

/**
 * Post
 *
 * A simple model for post in a blog. Just an example in tests.
 *
 * @package gugglegum\AbstractEntity\tests\models
 */
class Post extends Message
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string[]
     */
    private $labels = [];

    /**
     * @return string|null
     */
    public function getTitle()
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
