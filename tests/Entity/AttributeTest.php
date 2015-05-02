<?php
namespace inklabs\kommerce\Entity;

use inklabs\kommerce\View;
use Symfony\Component\Validator\Validation;

class AttributeTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $attribute = new Attribute;
        $attribute->setName('Test Attribute');
        $attribute->setDescription('Test attribute description');
        $attribute->setSortOrder(0);
        $attribute->addAttributeValue(new AttributeValue);
        $attribute->addProductAttribute(new ProductAttribute);

        $validator = Validation::createValidatorBuilder()
            ->addMethodMapping('loadValidatorMetadata')
            ->getValidator();

        $this->assertEmpty($validator->validate($attribute));
        $this->assertSame('Test Attribute', $attribute->getName());
        $this->assertSame('Test attribute description', $attribute->getDescription());
        $this->assertSame(0, $attribute->getSortOrder());
        $this->assertTrue($attribute->getAttributeValues()[0] instanceof AttributeValue);
        $this->assertTrue($attribute->getProductAttributes()[0] instanceof ProductAttribute);
        $this->assertTrue($attribute->getView() instanceof View\Attribute);
    }
}
