<?php

namespace Controllers;

use DateTime;
use DI\Container;
use Exception;
use logs\FileLog;
use models\Log;
use models\Requester;
use models\WebsiteRequest;
use models\WebsiteResponse;
use PDO;
use PDOException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use function DI\get;


require 'models\Requester.php';

require 'models\WebsiteResponse.php';

require 'models\WebsiteRequest.php';

require 'models\Log.php';

require 'logs\FileLog.php';

ini_set('memory_limit', '10000M');


class UploadLogController
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function upload_log(Request $request, Response $response)
    {
        if (!isset($_SESSION['logs']))
        {
            $fileLogs = $this->getFileLogs();

            $_SESSION['logs'] = $fileLogs;
        }

        $this->insertBlockBD($response);
    }

    public function insertBlockBD(Response $response)
    {
        $start_time = microtime(true);

        $logs = $_SESSION['logs'];

        $blockData = 1905;

        $sizeLogsBefore = count($logs);

        $firstElementsLogs = array_splice($logs, 0, $blockData);
        $_SESSION['logs'] = $logs;

        $this->container->get('db')->beginTransaction();

        try {

            foreach ($firstElementsLogs as $log) {

                $req = new Requester($log->getIp(), $log->getUserAgent());

                Requester::insert($this->container->get('db'), $req);

                $wbResp = new WebsiteResponse($log->getStatus(), $log->getSize());

                WebsiteResponse::insert($this->container->get('db'), $wbResp);

                $wbReq = new WebsiteRequest($req->getRequesterId(), $log->getRequestMethod(), $log->getTimestamp(), $log->getReferrer(), $log->getRequestURI());

                WebsiteRequest::insert($this->container->get('db'), $wbReq);

                Log::insert($this->container->get('db'), new Log($wbResp->getWebsiteResponseId(), $wbReq->getWebsiteRequestId()));
            }

            $this->container->get('db')->commit();

        } catch (Exception $e) {
            $this->container->get('db')->rollBack();
            throw $e;
        }

        $sizeLogsAfter = count($logs);

        $execution_time = microtime(true) - $start_time;

        $data = [
            "lengthLogsBefore" => $sizeLogsBefore,
            "lengthLogsAfter" => $sizeLogsAfter,
            "sizeBlockData" => $blockData,
            "timeInsertBlockData" => $execution_time
        ];


        $response->getBody()->write(json_encode($data));

    }

    public function getFileLogs()
    {
        ini_set('memory_limit', '1000M');
        ini_set('max_execution_time', '1000');
        $filename = 'logs/log200k.log';
        $regex = '/^(\d+\.\d+\.\d+\.\d+) - - \[([^\]]+)\] "(\w+) ([^"]*)" (\d+) (\d+) "([^"]*)" "([^"]*)"/';

        $file = fopen($filename, 'r');

        $fileLogs = [];

        if ($file) {
            while (!feof($file)) {
                $line = fgets($file);
                $matches = array();
                if (preg_match($regex, $line, $matches)) {
                    // Извлекаем данные и записываем их в файл
                    $ip = $matches[1];
                    $dateStr = $matches[2];
                    $requestMethod = $matches[3];
                    $url = $matches[4];
                    $requestURI = explode(" ", $url)[0];
                    $status = $matches[5];
                    $size = $matches[6];
                    $referrer = $matches[7];
                    $userAgent = $matches[8];

                    $date = DateTime::createFromFormat('d/M/Y:H:i:s O', $dateStr);
                    $timestamp = $date->format('Y-m-d H:i:s');

                    $fileLog = new FileLog();

                    $fileLog->setIp($ip);
                    $fileLog->setTimestamp($timestamp);
                    $fileLog->setRequestMethod($requestMethod);
                    $fileLog->setUrl($url);
                    $fileLog->setRequestURI($requestURI);
                    $fileLog->setStatus($status);
                    $fileLog->setSize($size);
                    $fileLog->setReferrer($referrer);
                    $fileLog->setUserAgent($userAgent);

                    $fileLogs[] = $fileLog;

                }
            }

            fclose($file);
        } else {
            echo "Не удалось открыть файл.";
        }


        return $fileLogs;

    }

    public function getLogs(): array
    {
        return Log::select($this->container->get('db'));
    }

    private function getReferrer($referrer) {
        if (strpos($referrer, '-') !== false) {
            return null;
        } else {
            return $referrer;
        }
    }

}