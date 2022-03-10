<?php

declare(strict_types=1);

use gugglegum\AbstractEntity\EntityInterface;
use gugglegum\AbstractEntity\EntityTrait;
use gugglegum\AbstractEntity\GettersAndSettersTrait;
use gugglegum\AbstractEntity\PlainObjectTrait;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Example 2
 *
 * Create empty Post entity (which is child class of the Message), init partially by associative array, the rest
 * attributes are initialized by setter methods. And then print out all attributes as associative array.
 */

class Message  implements EntityInterface
{
    use EntityTrait,
        GettersAndSettersTrait,
        PlainObjectTrait;

    private DateTime $datetime;
    private int $userId;
    private string $text;

    public function getDatetime(): DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(DateTime $datetime): self
    {
        $this->datetime = $datetime;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }
}

class Post extends Message
{
    private string $title;

    /** @var string[] */
    private array $labels = [];

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getLabels(): array
    {
        return $this->labels;
    }

    public function setLabels(array $labels): self
    {
        $this->labels = $labels;
        return $this;
    }
}

$post = new Post();
$post->setFromArray([
    'userId' => 1,
    'title' => 'Hello world',
]);
$post->setAttribute('datetime', new DateTime('now'));
$post->setLabels(['test', 'example']);

var_dump($post->toArray());
