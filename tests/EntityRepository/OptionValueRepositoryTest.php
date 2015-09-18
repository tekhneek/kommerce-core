<?php
namespace inklabs\kommerce\EntityRepository;

use inklabs\kommerce\Entity;
use inklabs\kommerce\tests\Helper;

class OptionValueRepositoryTest extends Helper\DoctrineTestCase
{
    protected $metaDataClassNames = [
        'kommerce:Option',
        'kommerce:OptionValue',
    ];

    /** @var OptionValueRepositoryInterface */
    protected $optionValueRepository;

    public function setUp()
    {
        $this->optionValueRepository = $this->repository()->getOptionValueRepository();
    }

    private function setupOptionValue()
    {
        $option = $this->getDummyOption();
        $optionValue = $this->getDummyOptionValue($option);

        $this->entityManager->persist($option);

        $this->optionValueRepository->create($optionValue);

        $this->entityManager->flush();
        $this->entityManager->clear();

        return $optionValue;
    }

    public function testCRUD()
    {
        $optionValue = $this->setupOptionValue();

        $optionValue->setName('New Name');
        $this->assertSame(null, $optionValue->getUpdated());
        $this->optionValueRepository->save($optionValue);
        $this->assertTrue($optionValue->getUpdated() instanceof \DateTime);

        $this->optionValueRepository->remove($optionValue);
        $this->assertSame(null, $optionValue->getId());
    }

    public function testFind()
    {
        $this->setupOptionValue();

        $this->setCountLogger();

        $optionValue = $this->optionValueRepository->find(1);

        $optionValue->getOption()->getCreated();

        $this->assertTrue($optionValue instanceof Entity\OptionValue);
        $this->assertSame(1, $this->countSQLLogger->getTotalQueries());
    }

    public function testGetAllOptionValuesByIds()
    {
        $this->setupOptionValue();

        $this->setCountLogger();

        $optionValues = $this->optionValueRepository->getAllOptionValuesByIds([1]);

        $optionValues[0]->getOption()->getCreated();

        $this->assertTrue($optionValues[0] instanceof Entity\OptionValue);
        $this->assertSame(1, $this->countSQLLogger->getTotalQueries());
    }
}