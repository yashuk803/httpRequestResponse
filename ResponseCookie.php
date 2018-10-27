<?php


final class ResponseCookie {
    /** @var string */
    private $name;

    /** @var string */
    private $value;

    /**
     * @param string           $name Name of the cookie.
     * @param string           $value Value of the cookie.
     */
    public function __construct(
        string $name,
        string $value = ''
    ) {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string Name of the cookie.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string Value of the cookie.
     */
    public function getValue(): string {
        return $this->value;
    }

}
