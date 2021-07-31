<?php declare(strict_types=1);

namespace Invariance\Datapunk\Lib\Systems;

use Aura\Router\RouterContainer;
use Invariance\Datapunk\Component\GetBlogPostComponent;
use Invariance\Datapunk\Ecs\System\EcsExecuteSystem;

class RoutingSystem implements EcsExecuteSystem
{
    public function execute(EcsContext $context): void
    {
        $routerContainer = new RouterContainer();
        $matcher = $routerContainer->getMatcher();
        $map = $routerContainer->getMap();

        $map->get('blog.read', '/blog/{id}', function (ServerRequestInterface $req) {
            $entity = $this->context->makeEntity();
            $entity->replace(new GetBlogPostComponent($req->getAttribute('id')));
        })->tokens(['id' => '\d+']);

        $route = $matcher->match(IOHandler::getRequest());

        if (!$route) {
            $failedRoute = $matcher->getFailedRoute();

            switch ($failedRoute->failedRule) {
                case 'Aura\Router\Rule\Allows':
                    IOHandler::response(new Response(405, [], 'Method not allowed (405)'));
                    break;
                case 'Aura\Router\Rule\Accepts':
                    IOHandler::response(new Response(406, [], 'Method not acceptable (406)'));
                    break;
                default:
                    IOHandler::response(new Response(404, [],'Method not found (404)'));
                    break;
            }
        }
    }
}
