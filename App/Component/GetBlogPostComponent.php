<?php declare(strict_types=1);

namespace Invariance\Datapunk\Component;

use Invariance\Datapunk\Ecs\EcsComponent;

class GetBlogPostComponent implements EcsComponent
{
    public int $id;

    public function __construct(int $id) {
        $this->id = $id;
    }
}
