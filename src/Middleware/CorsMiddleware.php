<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class CorsMiddleware implements MiddlewareInterface {

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		// プリフライト（OPTIONS）リクエストへの即時応答
		if ($request->getMethod() === 'OPTIONS') {
			$response = new \Laminas\Diactoros\Response\EmptyResponse(200);
		} else {
			$response = $handler->handle($request);
		}

		// CORSヘッダーを付与
		return $response
										->withHeader('Access-Control-Allow-Origin', '*')
										->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
										->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
										->withHeader('Access-Control-Max-Age', '86400');
	}
}
