<?php
namespace inklabs\kommerce\Action\Order\Query;

use inklabs\kommerce\Lib\Uuid;
use inklabs\kommerce\Lib\UuidInterface;

final class GetOrdersByUserRequest
{
    /** @var UuidInterface */
    private $userId;

    /**
     * @param string $userId
     */
    public function __construct($userId)
    {
        $this->userId = Uuid::fromString($userId);
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
