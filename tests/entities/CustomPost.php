<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

/**
 * Custom Post
 *
 * The same as Post but uses associative array in private field `attributes` to store attribute values. As opposed
 * to the CustomUser class this class doesn't redefine exception class (to create a variety in the tests).
 */
class CustomPost extends Message
{
    private array $attributes = [
        'title' => null,
        'labels' => [],
    ];

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->attributes['title'];
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->attributes['title'] = $title;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getLabels(): array
    {
        return $this->attributes['labels'];
    }

    /**
     * @param string[] $labels
     * @return self
     */
    public function setLabels(array $labels): self
    {
        $this->attributes['labels'] = $labels;
        return $this;
    }

    /**
     * @return string[]
     */
    public static function getAttributeNames(): array
    {
        return array_merge((parent::class)::getAttributeNames(), [
            'title',
            'labels',
        ]);
    }

    /**
     * Check is attribute initialized
     *
     * @param string $key
     * @return bool
     */
    public function issetAttribute(string $key): bool
    {
        return isset($this->attributes[$key]) || parent::issetAttribute($key);
    }
}
