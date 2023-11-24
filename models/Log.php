<?php

namespace models;

use dto\LoggerDTO;
use PDO;

require 'dto\LoggerDTO.php';

class Log
{
    private static $selectLogsCommand = "
            SELECT
            requesters.ip_address,
            website_requests.timestamp,
            website_requests.request_url,
            website_requests.http_version,
            website_responses.status_code,
            website_responses.response_size,
            website_requests.referrer,
            requesters.user_agent,
            COUNT(*) OVER (PARTITION BY requesters.ip_address) AS ip_address_count
        FROM logs
        JOIN website_requests ON logs.website_request_id = website_requests.id
        JOIN website_responses ON logs.website_response_id = website_responses.id
        JOIN requesters ON website_requests.requester_id = requesters.id;
    ";
    private static string $insertLogCommand = "INSERT INTO logs (website_request_id, website_response_id) VALUES (:reqId, :respId)";

    private $websiteRequestId;
    private $websiteResponseId;


    public function __construct($websiteRequestId, $websiteResponseId)
    {
        $this->websiteRequestId = $websiteRequestId;
        $this->websiteResponseId = $websiteResponseId;
    }


    public static function insert(PDO $pdo, Log $log)
    {
        $stmt = $pdo->prepare(self::$insertLogCommand);

        $stmt->bindParam(':reqId', $log->websiteRequestId, PDO::PARAM_INT);
        $stmt->bindParam(':respId', $log->websiteResponseId, PDO::PARAM_INT);

        $stmt->execute();
    }

    public static function select(PDO $pdo)
    {
        ini_set('memory_limit', '1000M');

        $stmt = $pdo->prepare(self::$selectLogsCommand);

        $loggers = array();

        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {

                $loggers[] = new LoggerDTO($row['ip_address'], $row['timestamp'], $row['request_url'],
                    $row['http_version'], $row['status_code'], $row['response_size'], $row['referrer'], $row['user_agent'], $row['ip_address_count']);

            }
        } else {
            echo "Ошибка при выполнении запроса: " . implode(', ', $stmt->errorInfo());
        }

        return $loggers;
    }

}