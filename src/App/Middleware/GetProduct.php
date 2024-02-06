<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;
use App\Repositories\ProductRepository;
use Slim\Exception\HttpNotFoundException;

class GetProduct
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $context = RouteContext::fromRequest($request);

        $route = $context->getRoute();

        $id = $route->getArgument('id');

        $product = $this->repository->getById((int) $id);
    
        if ($product === false) {
    
            throw new HttpNotFoundException($request,message: 'product not found');
    
        }

        $request = $request->withAttribute('product', $product);

        return $handler->handle($request);
    }
}