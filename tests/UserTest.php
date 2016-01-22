<?php declare(strict_types = 1);

/**
 * @covers User
 *
 * @uses Message
 */
class UserTest extends PHPUnit_Framework_TestCase
{
    use UserHelperTrait;

    public function testPublishesMessageToOtherUsers()
    {
        $sender = new User($this->mockNickname(), $this->mockEmail());
        $recipients = $this->mockUser();
        $recipients->expects($this->once())->method('receive');

        $message = new Message('Hello World');
        $sender->addFollower($recipients);
        $sender->publish($message);
    }

    public function testReceivesMessagesFromFollowingUsers()
    {
        $user = new User($this->mockNickname(), $this->mockEmail());
        $sender = new User($this->mockNickname(), $this->mockEmail());
        $sender->addFollower($user);

        ob_start();
        $sender->publish(new Message('Hello World'));
        $output = ob_get_clean();
        $this->assertEquals('Hello World' . PHP_EOL, $output);
    }

    public function testUnfollowedUserDoesNotReceiveMessages()
    {
        $sender = new User($this->mockNickname(), $this->mockEmail());
        $recipients = $this->mockUser();
        $recipients->expects($this->never())->method('receive');

        $message = new Message('Hello World');
        $sender->addFollower($recipients);
        $sender->removeFollower($recipients);
        $sender->publish($message);
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
