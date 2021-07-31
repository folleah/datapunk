<?php declare(strict_types=1);

namespace Invariance\Datapunk\Lib;

use Invariance\Datapunk\Ecs\EcsContext;
use Invariance\Datapunk\Ecs\EcsSystemsContainer;
use Invariance\Datapunk\Lib\Systems\Post\GetPostSystem;
use Invariance\Datapunk\Lib\Systems\RoutingSystem;

class App
{
    public EcsContext $context;
    public EcsSystemsContainer $systems;

    public function __construct()
    {
        $this->context = new EcsContext();
        $this->systems = new EcsSystemsContainer($this->context);
        $this->systems
            ->add(new RoutingSystem())
            ->add(new GetPostSystem())
            ->add(new UserInitSystem())
            ->add(new UserAuthenticationSystem())
            ->inject(new DatabaseConfig('db'))
        ;
        $this->systems->init();
    }

    public function run(): void
    {
        $this->systems->execute();
    }
}
