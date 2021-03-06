<?php
namespace inklabs\kommerce\Service;

use inklabs\kommerce\EntityRepository\ImageRepositoryInterface;
use inklabs\kommerce\EntityRepository\ProductRepositoryInterface;
use inklabs\kommerce\EntityRepository\TagRepositoryInterface;
use inklabs\kommerce\tests\Helper\TestCase\ServiceTestCase;

class ImageServiceTest extends ServiceTestCase
{
    /** @var ImageRepositoryInterface | \Mockery\Mock */
    protected $imageRepository;

    /** @var ProductRepositoryInterface | \Mockery\Mock */
    private $productRepository;

    /** @var TagRepositoryInterface | \Mockery\Mock */
    protected $tagRepository;

    /** @var ImageService */
    protected $imageService;

    public function setUp()
    {
        parent::setUp();

        $this->imageRepository = $this->mockRepository->getImageRepository();
        $this->productRepository = $this->mockRepository->getProductRepository();
        $this->tagRepository = $this->mockRepository->getTagRepository();
        $this->imageService = new ImageService(
            $this->imageRepository,
            $this->productRepository,
            $this->tagRepository
        );
    }

    public function testCRUD()
    {
        $this->executeServiceCRUD(
            $this->imageService,
            $this->imageRepository,
            $this->dummyData->getImage()
        );
    }

    public function testCreateFromDTOWithTag()
    {
        $imageDTO = $this->getDTOBuilderFactory()
            ->getImageDTOBuilder($this->dummyData->getImage())
                ->build();

        $tag = $this->dummyData->getTag();
        $this->tagRepository->shouldReceive('findOneById')
            ->with($tag->getId())
            ->andReturn($tag)
            ->once();

        $this->imageRepository->shouldReceive('create')
            ->once();

        $this->imageService->createFromDTOWithTag($tag->getId(), $imageDTO);
    }

    public function testCreateFromDTOWithProduct()
    {
        $imageDTO = $this->getDTOBuilderFactory()
            ->getImageDTOBuilder($this->dummyData->getImage())
                ->build();

        $product = $this->dummyData->getProduct();
        $this->productRepository->shouldReceive('findOneById')
            ->with($product->getId())
            ->andReturn($product)
            ->once();

        $this->imageRepository->shouldReceive('create')
            ->once();

        $this->imageService->createFromDTOWithProduct($product->getId(), $imageDTO);
    }

    public function testFindOneById()
    {
        $image1 = $this->dummyData->getTag();
        $this->imageRepository->shouldReceive('findOneById')
            ->with($image1->getId())
            ->andReturn($image1)
            ->once();

        $image = $this->imageService->findOneById($image1->getId());

        $this->assertEntitiesEqual($image1, $image);
    }
}
