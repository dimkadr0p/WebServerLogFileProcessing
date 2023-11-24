<?php

namespace logs;

class FileLog
{
    private $ip;
    private $timestamp;
    private $requestMethod;
    private $url;
    private $requestURI;
    private $status;
    private $size;
    private $referrer;
    private $userAgent;


    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip): void
    {
        $this->ip = $ip;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    public function setRequestMethod($requestMethod): void
    {
        $this->requestMethod = $requestMethod;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url): void
    {
        $this->url = $url;
    }

    public function getRequestURI()
    {
        return $this->requestURI;
    }

    public function setRequestURI($requestURI): void
    {
        $this->requestURI = $requestURI;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size): void
    {
        $this->size = $size;
    }

    public function getReferrer()
    {
        return $this->referrer;
    }

    public function setReferrer($referrer): void
    {
        $this->referrer = $referrer;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function setUserAgent($userAgent): void
    {
        $this->userAgent = $userAgent;
    }

}