<?php

declare(strict_types=1);

namespace App\DataFixtures\Test;

use App\DataFixtures\TestFixturesAbstract;
use App\Service\Helper\TestHelper;
use Doctrine\Persistence\ObjectManager;
use Dullahan\Entity\User;
use Dullahan\Entity\UserData;
use Dullahan\Service\Util\BinUtilService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends TestFixturesAbstract
{
    public const NORMAL_USER = 'normal_user';
    public const DEACTIVATED_USER = 'deactivated_user';
    public const SUPER_USER = 'super_user';
    public const CREATOR = 'creator_user';

    public function __construct(
        protected UserPasswordHasherInterface $hasher
    ) {
    }

    public static function getGroups(): array
    {
        return array_merge(['admin'], parent::getGroups());
    }

    public function load(ObjectManager $manager): void
    {
        $users = [];
        $data = new UserData();
        $data->setName(TestHelper::USER_NAME)
            ->setPublicId('');
        $user = (new User())
            ->setEmail(TestHelper::USER_EMAIL)
            ->setData($data)
            ->setRoles(['ROLE_USER'])
            ->setActivated(true)
            ->setWhenActivated(time())
        ;
        $users[] = $user;
        $this->setHashedPassword($user, TestHelper::USER_PLAIN_PASSWORD);
        $manager->persist($user);
        $this->setReference(self::NORMAL_USER, $user);

        $data = new UserData();
        $data->setName(TestHelper::DEACTIVATED_USER_NAME)
            ->setPublicId('');
        $user = (new User())
            ->setEmail(TestHelper::DEACTIVATED_USER_EMAIL)
            ->setData($data)
            ->setRoles(['ROLE_USER'])
            ->setActivated(false)
        ;
        $users[] = $user;
        $this->setHashedPassword($user, TestHelper::DEACTIVATED_USER_PLAIN_PASSWORD);
        $manager->persist($user);
        $this->setReference(self::DEACTIVATED_USER, $user);

        $data = new UserData();
        $data->setName(TestHelper::SUPER_USER_NAME)
            ->setPublicId('');
        $superUser = (new User())
            ->setEmail(TestHelper::SUPER_USER_EMAIL)
            ->setData($data)
            ->setRoles(['ROLE_SUPER_USER'])
            ->setActivated(true)
            ->setWhenActivated(time())
        ;
        $users[] = $superUser;
        $this->setHashedPassword($superUser, TestHelper::SUPER_USER_PLAIN_PASSWORD);
        $manager->persist($superUser);
        $this->setReference(self::SUPER_USER, $superUser);

        $data = new UserData();
        $data->setName(TestHelper::CREATOR_NAME)
            ->setPublicId('');
        $creatorCod = (new User())
            ->setEmail(TestHelper::CREATOR_EMAIL)
            ->setData($data)
            ->setRoles(['ROLE_USER'])
            ->setActivated(true)
            ->setWhenActivated(time())
        ;
        $users[] = $creatorCod;
        $this->setHashedPassword($creatorCod, TestHelper::CREATOR_PLAIN_PASSWORD);
        $manager->persist($creatorCod);
        $this->setReference(self::CREATOR, $creatorCod);

        $manager->flush();

        foreach ($users as $user) {
            /** @var UserData $data */
            $data = $user->getData();
            $data->setPublicId((new BinUtilService())->generateUniqueToken((string) $user->getId()));
            $manager->persist($data);
        }
        $manager->flush();
    }

    protected function setHashedPassword(User $admin, string $plainPassword): void
    {
        $hashedPassword = $this->hasher->hashPassword(
            $admin,
            $plainPassword
        );
        $admin->setPassword($hashedPassword);
    }
}
