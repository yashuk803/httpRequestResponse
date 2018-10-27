<?php



class Response
{
    /**
     * @var ResponseHeaderBag
     */
    public $headers;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var string
     */
    protected $statusText;


    public function __construct($content = '', int $status = 200, array $headers = array())
    {
        $this->headers = new ResponseHeaderBag($headers);
        $this->setContent($content);
        $this->setStatus($status);

    }


    /**
     * Sets the response content.
     *
     * @param mixed $content Content that can be cast to string
     *
     * @return $this
     *
     */
    public function setContent($content)
    {
        if (null !== $content && !\is_string($content) && !is_numeric($content) && !\is_callable(array($content, '__toString'))) {
            throw new \UnexpectedValueException(sprintf('The Response content must be a string "%s" given.', \gettype($content)));
        }

        $this->content = (string) $content;

        return $this;
    }

    /**
     * Gets the current response content.
     *
     * @return string Content
     */
    public function getContent()
    {
        return $this->content;
    }


    /**
     * Sets the response status code and reason phrase
     *
     * @param int $code 100 - 599
     * @param string|null $reason
     */
    public function setStatus(int $code, string $reason = null) {
        $this->status = $this->validateStatusCode($code);

    }

    /**
     * @param int $code
     *
     * @return int
     *
     */
    private function validateStatusCode(int $code): int {
        if ($code < 100 || $code > 599) {
            throw new \Error(
                'Invalid status code. Must be an integer between 100 and 599, inclusive.'
            );
        }

        return $code;
    }

    /**
     * Retrieves the status code for the current web response.
     *
     */
    public function getStatusCode(): int
    {
        return $this->status;
    }
}
