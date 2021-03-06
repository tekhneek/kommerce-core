<?php
namespace inklabs\kommerce\EntityDTO;

class TagDTO
{
    use IdDTOTrait, TimeDTOTrait;

    /** @var string */
    public $slug;

    /** @var string */
    public $name;

    /** @var string */
    public $code;

    /** @var string */
    public $description;

    /** @var string */
    public $defaultImage;

    /** @var int */
    public $sortOrder;

    /** @var bool */
    public $isVisible;

    /** @var bool */
    public $isActive;

    /** @var bool */
    public $areAttachmentsEnabled;

    /** @var ProductDTO[] */
    public $products = [];

    /** @var ImageDTO[] */
    public $images = [];

    /** @var OptionDTO[] */
    public $options = [];

    /** @var TextOptionDTO[] */
    public $textOptions = [];
}
