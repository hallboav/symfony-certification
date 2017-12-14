<?php
require __DIR__ . '/../vendor/autoload.php';

use App\EventListener\ContentApplicationJsonListener;
// use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('add_user_route', new Route(
    '/user', // path
    ['_controller' => 'App\Controller\RestController::user'],  // defaults
    [],  // requirements
    [], // options
    '', // domain name matching
    [], // schemes
    ['POST'], // HTTP methods matching
    '\'application/json\' === request.headers.get(\'Content-Type\')' // conditional request matching
));

$request = Request::createFromGlobals();
$stack = new RequestStack();
$matcher = new UrlMatcher($routes, new RequestContext());

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher, $stack));
$dispatcher->addSubscriber(new ContentApplicationJsonListener());

$container = new ContainerBuilder();
$container->setParameter('foo.bar', 'great!');

$parser = new \Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser();
$kernel = new HttpKernel($dispatcher, new ControllerResolver($container, $parser), $stack, new ArgumentResolver());

try {
    $response = $kernel->handle($request);
} catch (NotFoundHttpException $exception) {
    $response = new Response($exception->getMessage(), Response::HTTP_NOT_FOUND);
} catch (\Exception $exception) {
    $response = new Response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
}

$response->send();
$kernel->terminate($request, $response);
