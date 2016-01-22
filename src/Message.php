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
     * @param String $timestamp
     */
    public function __construct(String $message, String $timestamp = null)
    {
        $this->ensureMessageIsNotTooLong($message);

        $this->message = $message;

        if (null === $timestamp) {
            $timestamp = time().microtime(false);
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

    public function timeCreated() : String
    {
        return $this->timeCreated;
    }
}

