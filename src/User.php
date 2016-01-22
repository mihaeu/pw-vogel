<?php declare(strict_types = 1);

class User
{
    /**
     * @var Nickname
     */
    private $nickname;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var SplObjectStorage
     */
    private $followers;

    /**
     * @var Array
     */
    private $incomingMessages;

    /**
     * @param Nickname $nickname
     * @param Email $email
     */
    public function __construct(Nickname $nickname, Email $email)
    {
        $this->nickname = $nickname;
        $this->email = $email;

        $this->followers = new SplObjectStorage();
    }

    public function publish(Message $message)
    {
        foreach ($this->followers as $follower) {
            /** @var User $follower */
            $follower->receive($message);
        }
    }

    public function receive(Message $message)
    {
        echo $message . PHP_EOL;
        $this->incomingMessages[$message->timeCreated()] = $message;
    }

    /**
     * @param User $otherUser
     * @return bool
     */
    public function equals(User $otherUser) : bool
    {
        return $this->email->__toString() === $otherUser->email()->__toString();
    }

    /**
     * @return Email
     */
    public function email() : Email
    {
        return $this->email;
    }

    public function addFollower(User $user)
    {
        $this->followers->attach($user);
    }

    public function removeFollower(User $user)
    {
        $this->followers->detach($user);
    }
}
