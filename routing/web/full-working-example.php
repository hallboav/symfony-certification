<?php
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\LocaleListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$routes = new RouteCollection();
$routes->add('hello_route', new Route(
    '/{_locale}/hello/{name}.{_format}', // path
    [
        '_controller' => function ($name, Request $request) {
            $locales = [
                'pt' => 'Olá %s!',
                'en' => 'Hello %s!'
            ];

            if ('json' === $request->getRequestFormat()) {
                return new JsonResponse([
                    'name' => $name
                ]);
            }

            return new Response(sprintf($locales[$request->getLocale()], $name));
        },
        'name'    => 'anon.',
        '_format' => 'html'
    ],  // defaults
    [
        '_locale' => 'pt|en',
        '_format' => 'html|json'
    ],  // requirements
    [], // options
    'localhost', // domain name matching
    [], // schemes
    [
        'GET'
    ], // HTTP methods matching
    'request.headers.get(\'User-Agent\') matches \'/chrome/i\'' // conditional request matching
));

// ???
// Special internal routing attributes
// _route
// _route_params -> name, _format e _locale
// OR
// _locale "en|fr",
// _format "html|rss",
// ???

$request = Request::createFromGlobals();
$requestStack = new RequestStack();
$matcher = new UrlMatcher($routes, new RequestContext());

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher, $requestStack));
$dispatcher->addSubscriber(new LocaleListener($requestStack));

$kernel = new HttpKernel($dispatcher, new ControllerResolver(), $requestStack, new ArgumentResolver());

try {
    $response = $kernel->handle($request);
} catch (ResourceNotFoundException $exception) {
    $response = new Response($exception->getMessage(), Response::HTTP_NOT_FOUND);
} catch (\Exception $exception) {
    $response = new Response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
}

$response->send();
$kernel->terminate($request, $response);
