<?php

/**
 * Router class
 * @package 
 */
class Router
{
    // Properties
    private $request;

    // Allowed HTTP methods (to be extended)
    private $httpMethods = array(
        "GET",
        "POST"
    );

    /**
     * Constructor
     * @param RequestInterface $request 
     * @return void 
     */
    function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Called if method doesn't exist
     * @param string $name 
     * @param mixed $args 
     * @return void 
     */
    function __call(string $name, $args)
    {
        list($route, $method) = $args;

        if (!in_array(strtoupper($name), $this->httpMethods)) {
            $this->invalidMethodHandler();
        }

        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    /**
     * Removes trailing slashes from the end of the route.
     * @param string $route
     */
    private function formatRoute(string $route)
    {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    /**
     * Handles invalid method
     * @return void 
     */
    private function invalidMethodHandler()
    {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    /**
     * Default request handler
     * @return void 
     */
    private function defaultRequestHandler()
    {
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    /**
     * Resolves a route
     */
    function resolve()
    {
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        $formatedRoute = $this->formatRoute($this->request->requestUri);
        $method = $methodDictionary[$formatedRoute];

        if (is_null($method)) {
            $this->defaultRequestHandler();
            return;
        }

        echo call_user_func_array($method, array($this->request));
    }

    function __destruct()
    {
        $this->resolve();
    }
}
