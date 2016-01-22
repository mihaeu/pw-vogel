<?php declare(strict_types = 1);

/**
 * @covers Message
 */
class MessageTest extends PHPUnit_Framework_TestCase
{
    public function testLimitIs80Characters()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Maximum length is');
        new Message(str_repeat('.', 81));
    }

    public function testNormalMessageIsAccepted()
    {
        $message = new Message('Hello World');
        $this->assertEquals('Hello World', $message);
    }
}

