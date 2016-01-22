<?php declare(strict_types = 1);

class Message
{
    const MAX_LENGTH = 80;
    /**
     * @var String
     */
    private $message;

    /**
     * @var int
     */
    private $timeCreated;

    /**
     * Message constructor.
     * @param String $message
     * @param int $timestamp
     */
    public function __construct(String $message, int $timestamp = null)
    {
        $this->ensureMessageIsNotTooLong($message);

        $this->message = $message;

        if (null === $timestamp) {
            $timestamp = time();
        }
        $this->timeCreated = $timestamp;
    }

    private function ensureMessageIsNotTooLong(String $message)
    {
        if (strlen($message) > self::MAX_LENGTH) {
            throw new InvalidArgumentException('Maximum length is ' . self::MAX_LENGTH);
        }
    }

    public function __toString() : String
    {
        return $this->message;
    }

    public function timeCreated() : int
    {
        return $this->timeCreated;
    }
}

