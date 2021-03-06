<?php
namespace inklabs\kommerce\Action\Product\Query;

use inklabs\kommerce\EntityDTO\Builder\ProductDTOBuilder;
use inklabs\kommerce\Lib\Pricing;

class GetProductResponse implements GetProductResponseInterface
{
    /** @var ProductDTOBuilder */
    private $productDTOBuilder;

    /** @var Pricing */
    private $pricing;

    public function __construct(Pricing $pricing)
    {
        $this->pricing = $pricing;
    }

    public function setProductDTOBuilder(ProductDTOBuilder $productDTOBuilder)
    {
        $this->productDTOBuilder = $productDTOBuilder;
    }

    public function getProductDTO()
    {
        return $this->productDTOBuilder
            ->build();
    }

    public function getProductDTOWithAllData()
    {
        return $this->productDTOBuilder
            ->withAllData($this->pricing)
            ->build();
    }
}
