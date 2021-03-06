<?php
namespace inklabs\kommerce\EntityRepository;

use inklabs\kommerce\Entity\TaxRate;
use inklabs\kommerce\Exception\InvalidArgumentException;
use inklabs\kommerce\Lib\UuidInterface;

/**
 * @method TaxRate findOneById(UuidInterface $id)
 */
interface TaxRateRepositoryInterface extends RepositoryInterface
{
    /**
     * @return TaxRate[]
     */
    public function findAll();

    /**
     * @param string $zip5
     * @param string $state
     * @return TaxRate
     * @throws InvalidArgumentException
     */
    public function findByZip5AndState($zip5 = null, $state = null);
}
