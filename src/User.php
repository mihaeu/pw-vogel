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
     * @var SplObjectStorage
     */
    private $circleOfFriends;

    /**
     * @param Nickname $nickname
     * @param Email $email
     */
    public function __construct(Nickname $nickname, Email $email)
    {
        $this->nickname = $nickname;
        $this->email = $email;

        $this->followers = new SplObjectStorage();
        $this->circleOfFriends = new SplObjectStorage();
        $this->circleOfFriends->attach($this);
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

    public function follow(User $user)
    {
        $this->circleOfFriends->attach($user);
        $user->addFollower($this);
    }

    public function unfollow(User $user)
    {
        $this->circleOfFriends->detach($user);
        $user->removeFollower($this);
    }

    private function addFollower(User $user)
    {
        $this->followers->attach($user);
    }

    private function removeFollower(User $user)
    {
        $this->followers->detach($user);
    }
}
