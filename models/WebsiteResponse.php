<?php

namespace models;

use PDO;

class WebsiteResponse
{

    private static string $insertWebsiteResponseCommand = "INSERT INTO website_responses (status_code, response_size) VALUES (:code, :size)";

    private int $websiteResponseId;
    private string $statusCode;
    private string $responseSize;

    public function __construct($statusCode, $responseSize)
    {
        $this->statusCode = $statusCode;
        $this->responseSize = $responseSize;
    }

    public static function insert(PDO $pdo, WebsiteResponse  $websiteResponse)
    {
        $stmt = $pdo->prepare(self::$insertWebsiteResponseCommand);

        $stmt->bindParam(':code', $websiteResponse->statusCode, PDO::PARAM_STR);
        $stmt->bindParam(':size', $websiteResponse->responseSize, PDO::PARAM_STR);

        $stmt->execute();

        $websiteResponse->setWebsiteResponseId($pdo->lastInsertId());
    }

    public function getWebsiteResponseId(): int
    {
        return $this->websiteResponseId;
    }

    public function setWebsiteResponseId(int $websiteResponseId): void
    {
        $this->websiteResponseId = $websiteResponseId;
    }

}