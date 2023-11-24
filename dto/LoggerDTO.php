<?php

namespace dto;

class LoggerDTO
{
    public $ipAddress;
    public $timestamp;
    public $requestUrl;
    public $httpVersion;
    public $statusCode;
    public $responseSize;
    public $referrer;
    public $userAgent;
    public $ipAddressCount;

    public function __construct($ipAddress, $timestamp,
                                $requestUrl, $httpVersion,
                                $statusCode, $responseSize, $referrer, $userAgent, $ipAddressCount) {

        $this->ipAddress = $ipAddress;
        $this->timestamp = $timestamp;
        $this->requestUrl = $requestUrl;
        $this->httpVersion = $httpVersion;
        $this->statusCode = $statusCode;
        $this->responseSize = $responseSize;
        $this->referrer = $referrer;
        $this->userAgent = $userAgent;
        $this->ipAddressCount = $ipAddressCount;
    }

}