<?php

namespace utilities;

use Slim\Psr7\Response;

class JsonResponseUtil {
    public static function sendJsonResponse(Response $response, $data, $statusCode) {
        $response->getBody()->write(json_encode($data));
        return $response->withStatus($statusCode)->withHeader('Content-Type', 'application/json');
    }
}