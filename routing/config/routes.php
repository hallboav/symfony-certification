<?php
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();
$routes->add('foo_route', new Route(
    '/foo/begin/{begin}/limit/{limit}',
    [
        '_controller' => 'App\Controller\FooController::fooAction',
        'limit' => 10
    ],
    [
        'begin' => '\d+',
        'limit' => '\d+'
    ]
));

return $routes;
