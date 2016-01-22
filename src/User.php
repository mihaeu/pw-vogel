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
    private $timeline;

    /**
     * @param Nickname $nickname
     * @param Email $email
     */
    public function __construct(Nickname $nickname, Email $email)
    {
        $this->nickname = $nickname;
        $this->email = $email;

        $this->timeline = [];
        $this->followers = new SplObjectStorage();
        $this->circleOfFriends = new SplObjectStorage();
        $this->circleOfFriends->attach($this);
    }

    public function publish(Message $message)
    {
        foreach ($this->followers as $follower) {
            /** @var User $follower */
            $follower->receive($message, $this);
        }
    }

    public function receive(Message $message, User $from)
    {
        if (!$this->circleOfFriends->contains($from)) {
            throw new CannotSendMessageToStrangersException();
        }

        echo $message . PHP_EOL;
        $this->timeline[$message->timeCreated()] = [
            'from' => $from,
            'message' => $message,
        ];
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
        $this->removeTimelineEntriesFromUser($user);
        $user->removeFollower($this);
    }

    public function viewTimeline()
    {
        if (empty($this->timeline)) {
            echo 'No messages';
        }

        foreach ($this->timeline as $time => $entry) {
            echo $entry['from']->email() . ' '
                . $time . ' '
                . $entry['message'];
        }
    }

    private function addFollower(User $user)
    {
        $this->followers->attach($user);
    }

    private function removeFollower(User $user)
    {
        $this->followers->detach($user);
    }

    private function removeTimelineEntriesFromUser(User $user)
    {
        foreach ($this->timeline as $time => $entry) {
            if (isset($entry['from'])
                && $user === $entry['from']) {
                unset($this->timeline[$time]);
            }
        }
    }
}
