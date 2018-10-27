<?php
class Request
{
    /**
     * Request body parameters ($_POST).
     *
     * @var
     */
    public $request;

    /**
     * Query string parameters ($_GET).
     *
     * @var
     */
    public $query;

    /**
     * Server and execution environment parameters ($_SERVER).
     *
     * @var
     */
    public $server;

    /**
     * Uploaded files ($_FILES).
     *
     * @var
     */
    public $files;

    /**
     * Cookies ($_COOKIE).
     *
     * @var
     */
    public $cookies;

    /**
     * Headers (taken from the $_SERVER).
     *
     * @var
     */
    public $headers;

    /**
     * @var string|resource|false|null
     */
    protected $content;

    /**
     * @var string
     */
    protected $method;

    protected static $httpMethodParameterOverride = false;
    /**
     * @param array                $query      The GET parameters
     * @param array                $request    The POST parameters
     * @param array                $cookies    The COOKIE parameters
     * @param array                $files      The FILES parameters
     * @param array                $server     The SERVER parameters
     * @param string|resource|null $content    The raw body data
     */
    public function __construct(array $query = array(), array $request = array(),  array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {

        $this->request = new ParameterBag($request);
        $this->query = new ParameterBag($query);
        $this->cookies = new ParameterBag($cookies);
        $this->files = new FileBag($files);
        $this->server = new ServerBag($server);
        $this->headers = new HeaderBag($this->server->getHeaders());
        $this->content = $content;
        $this->method = null;
    }


    /**
     * Gets a "parameter" value from any bag.
     *
     *
     * @param string $key     The key
     * @param mixed  $default The default value if the parameter key does not exist
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {


        if ($this !== $result = $this->query->get($key, $this)) {
            return $result;
        }

        if ($this !== $result = $this->request->get($key, $this)) {
            return $result;
        }

        return $default;
    }

    /**
     * Sets the request method.
     *
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = null;
        $this->server->set('REQUEST_METHOD', $method);
    }

    /**
     * Gets the request "intended" method.
     *
     * @return string The request method
     *
     */
    public function getMethod()
    {
        if (null === $this->method) {
            $this->method = strtoupper($this->server->get('REQUEST_METHOD', 'GET'));

            if ('POST' === $this->method) {
                if ($method = $this->headers->get('X-HTTP-METHOD-OVERRIDE')) {
                    $this->method = strtoupper($method);
                } elseif (self::$httpMethodParameterOverride) {
                    $method = $this->request->get('_method', $this->query->get('_method', 'POST'));
                    if (\is_string($method)) {
                        $this->method = strtoupper($method);
                    }
                }
            }
        }

        return $this->method;
    }

    /**
     * Gets the "real" request method.
     *
     * @return string The request method
     *
     * @see getMethod()
     */
    public function getRealMethod()
    {
        return strtoupper($this->server->get('REQUEST_METHOD', 'GET'));
    }

    /**
     * Returns the request body content.
     *
     * @param bool $asResource If true, a resource will be returned
     *
     * @return string|resource The request body content or a resource to read the body stream
     *
     */
    public function getContent($asResource = false)
    {
        $currentContentIsResource = \is_resource($this->content);

        if (true === $asResource) {
            if ($currentContentIsResource) {
                rewind($this->content);

                return $this->content;
            }

            if (null === $this->content || false === $this->content) {
                $this->content = file_get_contents('php://input');
            }

            return $this->content;
        }
    }

}
