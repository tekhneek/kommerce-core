<?php
namespace inklabs\kommerce\ActionHandler\Tag;

use inklabs\kommerce\Action\Tag\UpdateTagCommand;
use inklabs\kommerce\tests\Helper\TestCase\ActionTestCase;

class UpdateTagHandlerTest extends ActionTestCase
{
    public function testHandle()
    {
        $tagService = $this->mockService->getTagService();
        $tagService->shouldReceive('update')
            ->once();

        $tagDTO = $this->getDTOBuilderFactory()
            ->getTagDTOBuilder($this->dummyData->getTag())
            ->build();

        $command = new UpdateTagCommand($tagDTO);
        $handler = new UpdateTagHandler($tagService);
        $handler->handle($command);
    }
}
