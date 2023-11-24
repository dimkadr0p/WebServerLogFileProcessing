<?php

namespace models;

use PDO;

class Requester
{
    private static string $insertRequesterCommand = "INSERT INTO requesters (ip_address, user_agent) VALUES (:ip, :useragent) ON CONFLICT (ip_address, user_agent) DO UPDATE SET ip_address = EXCLUDED.ip_address, user_agent = EXCLUDED.user_agent RETURNING id;";
    private int $requesterId;
    private string $ipAddress;
    private string $userAgent;

    public function __construct($ipAddress, $userAgent)
    {
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    public static function insert(PDO $pdo, Requester $requester)
    {
        $stmt = $pdo->prepare(self::$insertRequesterCommand);
        $stmt->bindParam(':ip', $requester->ipAddress, PDO::PARAM_STR);
        $stmt->bindParam(':useragent', $requester->userAgent, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $requester->setRequesterId($row['id']);
            } else {
                $requester->setRequesterId($pdo->lastInsertId());
            }
        }
    }

    public function getRequesterId(): int
    {
        return $this->requesterId;
    }

    public function setRequesterId(int $requesterId): void
    {
        $this->requesterId = $requesterId;
    }


}