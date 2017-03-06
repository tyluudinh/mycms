<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 12/29/15
 * Time: 9:02 AM
 */

namespace common\core\httpApi;

use common\core\oop\exceptions\ClassMustImplementException;
use common\core\oop\ObjectScalar;

abstract class ServiceHttpApiAbstract implements ServiceHttpApiInterface
{
    /**
     * @var ServiceHttpResponseInterface
     */
    protected $_responseHandler;

    protected $_wrapper = [
        'uri'     => '',
        'method'  => ServiceHttpApiInterface::METHOD_POST,
        'query'   => [],
        'headers' => [
            'Content-Type' => 'application/json; charset=utf-8',
            'Accept'       => 'application/json',
        ],
        'body'    => [],
    ];

    public function setUri($uri = null)
    {
        $this->_wrapper['uri'] = $uri;

        return $this;
    }

    public function setMethod($method = ServiceHttpApiInterface::METHOD_POST)
    {
        $this->_wrapper['method'] = $method;
    }

    public function setHeaders($headers = [])
    {
        $this->_wrapper['headers'] = $headers;

        return $this;
    }

    public function addHeader($key, $value = null)
    {
        $this->_wrapper['headers'][$key] = $value;

        return $this;
    }

    public function setBody($body = [])
    {
        $this->_wrapper['body'] = $body;

        return $this;
    }

    public function addToBody($key, $value = null)
    {
        $this->_wrapper['body'][$key] = $value;

        return $this;
    }

    public function setQuery($query = [])
    {
        $this->_wrapper['query'] = $query;

        return $this;
    }

    public function addQuery($key, $value = null)
    {
        $this->_wrapper['query'][$key] = $value;

        return $this;
    }

    public function setResponseHandler($className)
    {
        $this->_responseHandler = $className;

        return $this;
    }

    public function __construct($uri, $method = ServiceHttpApiInterface::METHOD_POST)
    {
        $this->setUri($uri);
        $this->setMethod($method);
    }

    /**
     * quick creating request object
     * @param $uri
     * @param string $method
     * @return ServiceHttpApiAbstract
     */
    public static function init($uri, $method = ServiceHttpApiInterface::METHOD_POST)
    {
        return new static($uri, $method);
    }

    public abstract function getHost();

    public function send()
    {
        $this->_wrapper = (new ObjectScalar())->setData($this->_wrapper);

        $sendingHeaders = [];
        foreach ($this->_wrapper['headers'] as $key => $val) {
            $sendingHeaders[] = $key . ': ' . $val;
        }

        $query = null;
        if (!empty($this->_wrapper['query'])) {
            $query = '?' . http_build_query($this->_wrapper['query']);
        }

        $ch = curl_init($this->getHost() . $this->_wrapper['uri'] . $query);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->_wrapper['method']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->_wrapper['body']));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $sendingHeaders);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        $rawResponse = curl_exec($ch);

        return $this->getResponse($rawResponse);
    }


    /**
     * @param $rawResponse
     * @return ServiceHttpResponseInterface
     */
    public function getResponse($rawResponse)
    {
        if ($this->_responseHandler === null) {
            return new ServiceHttpApiResponse($rawResponse);
        }

        $instance = new $this->_responseHandler($rawResponse);
        if (!$instance instanceof ServiceHttpResponseInterface) {
            throw new ClassMustImplementException("class $instance must be an instace of " . ServiceHttpResponseInterface::class);
        }

        return $instance;
    }

}