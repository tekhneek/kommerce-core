<?php
namespace inklabs\kommerce\Action\Cart;

use inklabs\kommerce\EntityDTO\OrderAddressDTO;
use inklabs\kommerce\Lib\Command\CommandInterface;
use inklabs\kommerce\Lib\Uuid;
use inklabs\kommerce\Lib\UuidInterface;

final class SetExternalShipmentRateCommand implements CommandInterface
{
    /** @var UuidInterface */
    private $cartId;

    /** @var string */
    private $shipmentRateExternalId;

    /** @var OrderAddressDTO */
    private $shippingAddressDTO;

    /**
     * @param string $cartId
     * @param string $shipmentRateExternalId
     * @param OrderAddressDTO $shippingAddressDTO
     */
    public function __construct(
        $cartId,
        $shipmentRateExternalId,
        OrderAddressDTO $shippingAddressDTO
    ) {
        $this->cartId = Uuid::fromString($cartId);
        $this->shipmentRateExternalId = (string) $shipmentRateExternalId;
        $this->shippingAddressDTO = $shippingAddressDTO;
    }

    public function getCartId()
    {
        return $this->cartId;
    }

    public function getShipmentRateExternalId()
    {
        return $this->shipmentRateExternalId;
    }

    public function getShippingAddressDTO()
    {
        return $this->shippingAddressDTO;
    }
}
