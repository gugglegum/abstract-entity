<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

use gugglegum\AbstractEntity\AbstractEntity;

class SuperEntity extends AbstractEntity
{
    private Message $message;
    private Post $post;
    private ListOfUsers $listOfUsers;
    private GroupsOfUsers $groupsOfUsers;
    private \stdClass $simpleObject;
    private \DateTime $dateTime;

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @param Message $message
     * @return self
     */
    public function setMessage(Message $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     * @return self
     */
    public function setPost(Post $post): self
    {
        $this->post = $post;
        return $this;
    }

    /**
     * @return ListOfUsers
     */
    public function getListOfUsers(): ListOfUsers
    {
        return $this->listOfUsers;
    }

    /**
     * @param ListOfUsers $listOfUsers
     * @return self
     */
    public function setListOfUsers(ListOfUsers $listOfUsers): self
    {
        $this->listOfUsers = $listOfUsers;
        return $this;
    }

    /**
     * @return GroupsOfUsers
     */
    public function getGroupsOfUsers(): GroupsOfUsers
    {
        return $this->groupsOfUsers;
    }

    /**
     * @param GroupsOfUsers $groupsOfUsers
     * @return self
     */
    public function setGroupsOfUsers(GroupsOfUsers $groupsOfUsers): self
    {
        $this->groupsOfUsers = $groupsOfUsers;
        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getSimpleObject(): \stdClass
    {
        return $this->simpleObject;
    }

    /**
     * @param \stdClass $simpleObject
     * @return self
     */
    public function setSimpleObject(\stdClass $simpleObject): self
    {
        $this->simpleObject = $simpleObject;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     * @return self
     */
    public function setDateTime(\DateTime $dateTime): self
    {
        $this->dateTime = $dateTime;
        return $this;
    }
}
