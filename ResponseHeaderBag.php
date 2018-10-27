<?php
class ResponseHeaderBag extends HeaderBag
{

    protected $cookies = array();

    public function __construct(array $headers = array())
    {
        parent::__construct($headers);

        if (!isset($this->headers['date'])) {
            $this->initDate();
        }
    }


    public function setCookie(Cookie $cookie)
    {
        $this->cookies[$cookie->getName()] = $cookie;
    }

    /**
     * Returns an array with all cookies.
     *
     * @return []
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Returns a Cookies value by name.
     *
     * @param string               $key     The header name
     * @param string|string[]|null $default The default value
     * @return string|null
     */

    public function getCookiName($key, $default = null)
    {
        foreach ($this->getCookies() as $cookie) {
            if($cookie->getName() == $key) {
                return $cookie->getValue();
            }
        }
    }


    private function initDate()
    {
        $now = \DateTime::createFromFormat('U', time());
        $now->setTimezone(new \DateTimeZone('UTC'));
        $this->set('Date', $now->format('D, d M Y H:i:s').' GMT');
    }
}
