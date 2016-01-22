<?php declare(strict_types = 1);

class Message
{
    const MAX_LENGTH = 80;
    /**
     * @var String
     */
    private $message;

    /**
     * Message constructor.
     * @param String $message
     */
    public function __construct(String $message)
    {
        $this->ensureMessageIsNotTooLong($message);

        $this->message = $message;
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
}

