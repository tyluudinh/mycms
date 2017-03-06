<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 6/16/16
 * Time: 11:11 AM
 */

namespace common\core\httpApi;

use common\Factory;

class ServiceHttpApiResponse implements ServiceHttpResponseInterface
{
    /**
     * @var array
     */
    protected $headers;

    /**
     * @var string
     */
    protected $body;

    public function __construct($rawResponse)
    {
        list($headers, $body) = explode("\r\n\r\n", $rawResponse, 2);

        $this->body = $body;

        $headers = explode("\n", $headers);

        $d = [];
        foreach ($headers as $key => $header) {
            if ($key === 0) {
                $d['Http-Code'] = $header;
            } else {
                list ($key, $value) = explode(': ', $header);
                $d[$key] = $value;
            }
        }

        $this->headers = $d;

    }

    public function getHeaders()
    {
        return Factory::createObject($this->headers);
    }

    public function getBody()
    {
        return Factory::createObject(json_decode($this->body, true), true);
    }

    public function isSuccess()
    {
        $successCodes = ["200", "100"];
        foreach ($successCodes as $code) {
            if (strpos($this->headers['Http-Code'], $code) !== false) {
                return true;
            }
        }

        return false;

    }

}