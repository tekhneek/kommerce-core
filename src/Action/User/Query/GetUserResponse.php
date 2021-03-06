<?php
namespace inklabs\kommerce\Action\User\Query;

use inklabs\kommerce\EntityDTO\Builder\UserDTOBuilder;

class GetUserResponse implements GetUserResponseInterface
{
    /** @var UserDTOBuilder */
    private $userDTOBuilder;

    public function setUserDTOBuilder(UserDTOBuilder $userDTOBuilder)
    {
        $this->userDTOBuilder = $userDTOBuilder;
    }

    public function getUserDTO()
    {
        return $this->userDTOBuilder
            ->build();
    }

    public function getUserDTOWithRolesAndTokens()
    {
        return $this->userDTOBuilder
            ->withRoles()
            ->withTokens()
            ->build();
    }
}
