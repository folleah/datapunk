<?php declare(strict_types=1);

namespace Invariance\Datapunk\Lib;

use Invariance\Datapunk\Ecs\EcsContext;
use Invariance\Datapunk\Ecs\EcsSystemsContainer;

class Bootstrap
{
    public EcsContext $context;
    public EcsSystemsContainer $systems;

    public function __construct()
    {
        $this->context = new EcsContext();
        $this->systems = new EcsSystemsContainer($this->context);

        foreach ($systems as $system) {
            $this->systems->add($system);
        }

        foreach ($injections as $injection) {
            $this->systems->inject($injection);
        }

        $this->systems->init();
    }

    public function run()
    {
        $this->systems->execute();
    }
}
