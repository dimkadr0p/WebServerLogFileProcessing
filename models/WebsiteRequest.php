<?php

namespace models;

use PDO;

class WebsiteRequest
{

    private static string $insertWebsiteRequestCommand = "INSERT INTO website_requests (requester_id, http_method, timestamp, referrer, request_url) VALUES (:requesterId, :method, :timestamp, :referrer, :request_url)";

    private int $websiteRequestId;
    private int $requesterId;
    private string $httpMethod;
    private string $timestamp;
    private string $referrer;
    private string $requestUrl;

    public function __construct($requesterId, $httpMethod, $timestamp, $referrer, $requestUrl)
    {
        $this->requesterId = $requesterId;
        $this->httpMethod = $httpMethod;
        $this->timestamp = $timestamp;
        $this->referrer = $referrer;
        $this->requestUrl = $requestUrl;
    }


    public static function insert(PDO $pdo, WebsiteRequest $websiteRequest)
    {
        $stmt = $pdo->prepare(self::$insertWebsiteRequestCommand);

        $stmt->bindParam(':method', $websiteRequest->httpMethod, PDO::PARAM_STR);
        $stmt->bindParam(':requesterId', $websiteRequest->requesterId, PDO::PARAM_INT);
        $stmt->bindParam(':timestamp', $websiteRequest->timestamp, PDO::PARAM_STR);
        $stmt->bindParam(':referrer', $websiteRequest->referrer, PDO::PARAM_STR);
        $stmt->bindParam(':request_url', $websiteRequest->requestUrl, PDO::PARAM_STR);

        $stmt->execute();

        $websiteRequest->setWebsiteRequestId($pdo->lastInsertId());

    }

    public function getWebsiteRequestId(): int
    {
        return $this->websiteRequestId;
    }

    public function setWebsiteRequestId(int $websiteRequestId): void
    {
        $this->websiteRequestId = $websiteRequestId;
    }
}