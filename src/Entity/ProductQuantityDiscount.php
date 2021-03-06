<?php
namespace inklabs\kommerce\Entity;

use DateTime;
use inklabs\kommerce\Exception\BadMethodCallException;
use inklabs\kommerce\Lib\PricingInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class ProductQuantityDiscount extends AbstractPromotion
{
    protected $customerGroup;

    /** @var int */
    protected $quantity;

    /** @var bool */
    protected $flagApplyCatalogPromotions;

    /** @var Product */
    protected $product;

    public function __construct(Product $product)
    {
        parent::__construct();
        $this->setFlagApplyCatalogPromotions(false);
        $this->product = $product;
        $product->addProductQuantityDiscount($this);
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        parent::loadValidatorMetadata($metadata);

        $metadata->addPropertyConstraint('quantity', new Assert\NotNull);
        $metadata->addPropertyConstraint('quantity', new Assert\Range([
            'min' => 0,
            'max' => 65535,
        ]));
    }

    /**
     * @param string $name
     * @throws BadMethodCallException
     */
    public function setName($name)
    {
        throw new BadMethodCallException('Unable to set name');
    }

    public function getName()
    {
        $name = 'Buy ' . $this->getQuantity() . ' or more for ';

        if ($this->type->isExact()) {
            $name .= $this->displayCents($this->getValue()) . ' each';
        } elseif ($this->type->isPercent()) {
            $name .= $this->getValue() . '% off';
        } elseif ($this->type->isFixed()) {
            $name .= $this->displayCents($this->getValue()) . ' off';
        }

        return $name;
    }

    private function displayCents($priceInCents)
    {
        return '$' . number_format(($priceInCents / 100), 2);
    }

    public function getPrice(PricingInterface $pricing)
    {
        return $pricing->getPrice(
            $this->product,
            $this->quantity
        );
    }

    public function setCustomerGroup($customerGroup)
    {
        $this->customerGroup = $customerGroup;
    }

    public function getCustomerGroup()
    {
        return $this->customerGroup;
    }

    public function setFlagApplyCatalogPromotions($flagApplyCatalogPromotions)
    {
        $this->flagApplyCatalogPromotions = $flagApplyCatalogPromotions;
    }

    public function getFlagApplyCatalogPromotions()
    {
        return $this->flagApplyCatalogPromotions;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = (int) $quantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function isValid(DateTime $date, $quantity)
    {
        return $this->isValidPromotion($date)
            and $this->isQuantityValid($quantity);
    }

    public function isQuantityValid($quantity)
    {
        if ($quantity >= $this->quantity) {
            return true;
        } else {
            return false;
        }
    }
}
