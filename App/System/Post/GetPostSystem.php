<?php declare(strict_types=1);

namespace Invariance\Datapunk\Lib\Systems\Post;

use Invariance\Ecs\EcsContext;
use Invariance\Datapunk\Component\GetBlogPostComponent;
use Invariance\Datapunk\Lib\IOHandler;
use Invariance\Ecs\Filter\EcsFilteredResult;
use Invariance\Ecs\Filter\EcsFilterIncluded;
use Invariance\Ecs\System\EcsExecuteSystem;
use React\Http\Message\Response;

class GetPostSystem implements EcsExecuteSystem
{
    private EcsContext $context;
    #[EcsFilterIncluded(GetBlogPostComponent::class)]
    private EcsFilteredResult $filteredEntities;

    public function execute(): void
    {
        foreach ($this->filteredEntities as $i => $_) {
            /** @var GetBlogPostComponent $userComponent */
            $getBlogPostComponent = $this->filteredEntities->getFirst($i);

            IOHandler::response(new Response($getBlogPostComponent->id));
        }
    }
}
