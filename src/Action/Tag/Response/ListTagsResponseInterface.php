<?php
namespace inklabs\kommerce\Action\Tag\Response;

use inklabs\kommerce\EntityDTO\PaginationDTO;
use inklabs\kommerce\EntityDTO\TagDTO;
use inklabs\kommerce\Lib\Query\ResponseInterface;

interface ListTagsResponseInterface extends ResponseInterface
{
    public function addTagDTO(TagDTO $tagDTO);
    public function getTagDTOs();
    public function setPaginationDTO(PaginationDTO $paginationDTO);
    public function getPaginationDTO();
}