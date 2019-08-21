<?php

namespace App\DataFixtures;

use App\Entity\BaseUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BaseUserFixtures extends BaseFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(20, "semantic_users", function ($i) {
           $baseUser = new BaseUser();
           $baseUser->setLogin($this->faker->email);
           $baseUser->setPlainPassword($this->faker->password);
           $baseUser->setRoles(["ROLE_SEMANTIC_USER"]);

           return $baseUser;
        });

        $manager->flush();
    }
}
