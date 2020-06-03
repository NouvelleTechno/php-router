<?php
include_once 'RequestInterface.php';

/**
 * Request management
 * @package 
 */
class Request implements RequestInterface
{
    /**
     * Constructor
     * @return void 
     */
    function __construct()
    {
        $this->populate();
    }

    /**
     * Method that populates the object using all $_SERVER content
     * @return void 
     */
    private function populate()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    /**
     * Switching all properties to camelCase (REQUEST_METHOD -> requestMethod)
     * @param string $string 
     * @return string 
     */
    private function toCamelCase(string $string)
    {
        // Going to lowercase
        $result = strtolower($string);

        // Collecting matches (_ followed by letter)
        preg_match_all('/_[a-z]/', $result, $matches);

        // Switching Match to uppercase without _
        foreach ($matches[0] as $match) {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }

        // Returning result
        return $result;
    }

    /**
     * Getting body of the request
     * @return void|array 
     */
    public function getBody()
    {
        if ($this->requestMethod === "GET") {
            return;
        }


        if ($this->requestMethod == "POST") {

            $body = array();
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            return $body;
        }
    }
}
