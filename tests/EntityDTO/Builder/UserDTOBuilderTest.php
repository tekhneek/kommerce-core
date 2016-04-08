<?php
namespace inklabs\kommerce\EntityDTO;

use inklabs\kommerce\tests\Helper\DoctrineTestCase;

class UserDTOBuilderTest extends DoctrineTestCase
{
    public function testBuild()
    {
        $user = $this->dummyData->getUser();
        $user->addUserToken($this->dummyData->getUserToken());
        $user->addUserRole($this->dummyData->getUserRole());
        $user->addUserLogin($this->dummyData->getUserLogin());

        $userDTO = $user->getDTOBuilder()
            ->withAllData()
            ->build();

        $this->assertTrue($userDTO instanceof UserDTO);
        $this->assertTrue($userDTO->userRoles[0] instanceof UserRoleDTO);
        $this->assertTrue($userDTO->userTokens[0] instanceof UserTokenDTO);
        $this->assertTrue($userDTO->userLogins[0] instanceof UserLoginDTO);
    }
}
