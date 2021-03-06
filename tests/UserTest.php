<?php declare(strict_types = 1);

/**
 * @covers User
 * @covers CannotSendMessageToStrangersException
 *
 * @uses Message
 */
class UserTest extends PHPUnit_Framework_TestCase
{
    use UserHelperTrait;

    public function testReceivesMessagesFromFollowingUsers()
    {
        $user = new User($this->mockNickname(), $this->mockEmail());
        $sender = new User($this->mockNickname(), $this->mockEmail());
        $user->follow($sender);

        ob_start();
        $sender->publish(new Message('Hello World'));
        $output = ob_get_clean();
        $this->assertEquals('Hello World' . PHP_EOL, $output);
    }

    public function testUnfollowedUserDoesNotReceiveMessages()
    {
        $sender = new User($this->mockNickname(), $this->mockEmail());
        $recipients = new User($this->mockNickname(), $this->mockEmail());

        $message = new Message('Hello World');
        $recipients->follow($sender);
        $recipients->unfollow($sender);
        ob_start();
        $sender->publish($message);
        $output = ob_get_clean();
        $this->assertEmpty($output);
    }

    public function testOutputForEmptyTimeline()
    {
        $user = new User($this->mockNickname(), $this->mockEmail());
        ob_start();
        $user->viewTimeline();
        $output = ob_get_clean();
        $this->assertEquals($output, 'No messages');
    }

    public function testCanViewTimeline()
    {
        $sender = new User($this->mockNickname(), $this->mockEmail('mocki@aol.com'));
        $sender2 = new User($this->mockNickname(), $this->mockEmail('mocki2@aol.com'));
        $recipient = new User($this->mockNickname(), $this->mockEmail());
        $recipient->follow($sender);
        $recipient->follow($sender2);

        ob_start();
        $sender->publish(new Message('Hello World'));
        $sender2->publish(new Message('Hallo Welt'));
        ob_clean();
        $recipient->viewTimeline();
        $output = ob_get_clean();
        $this->assertRegExp('/mocki.*Hello.*mocki2.*Hallo/m', $output);
    }

    public function testNoMessagesOnTimelineFromUnfollowedUsers()
    {
        $user = new User($this->mockNickname(), $this->mockEmail());
        $sender = new User($this->mockNickname(), $this->mockEmail());
        $user->follow($sender);

        ob_start();
        $sender->publish(new Message('Hello World'));
        ob_clean();
        $user->unfollow($sender);
        $user->viewTimeline();
        $output = ob_get_clean();
        $this->assertEquals('No messages', $output);
    }

    public function testCannotFollowUserWhenBlacklisted()
    {
        $user = new User($this->mockNickname(), $this->mockEmail());
        $badGuy = new User($this->mockNickname(), $this->mockEmail());
        $user->addUserToBlacklist($badGuy);

        $this->setExpectedException(CannotAddBlacklistedUserException::class);
        $badGuy->follow($user);
    }

    public function testCannotReceiveFromUsersWhoAreNotFriends()
    {
        $user = new User($this->mockNickname(), $this->mockEmail());

        $this->setExpectedException(CannotSendMessageToStrangersException::class);
        $user->receive(new Message('Hello World'), $this->mockUser());
    }

    public function testUsersWithSameEmailAreEqual()
    {
        $user1 = new User($this->mockNickname(), $this->mockEmail('some@email.com'));
        $user2 = new User($this->mockNickname(), $this->mockEmail('some@email.com'));
        $this->assertTrue($user1->equals($user2));
    }

    public function testUsersWithDifferentEmailsAreNotEqual()
    {
        $user1 = new User($this->mockNickname(), $this->mockEmail('one@email.com'));
        $user2 = new User($this->mockNickname(), $this->mockEmail('other@email.com'));
        $this->assertFalse($user1->equals($user2));
    }
}
