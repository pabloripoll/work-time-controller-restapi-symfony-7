<?php

declare(strict_types=1);

namespace App\Domain\Admin\Fixture;

use App\Domain\Admin\Entity\Admin;
use App\Domain\Admin\Entity\AdminProfile;
use App\Domain\Employee\Entity\Employee;
use App\Domain\Shared\ValueObject\Email;
use App\Domain\User\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    private const SUPERADMIN_EMAIL = 'wortic.superadmin@example.com';
    private const SUPERADMIN_NICKNAME = 'SuperAdmin';
    private const SUPERADMIN_PASSWORD = 'Pass123A';
    private const EMPLOYEE_EMAIL = 'wortic.admin@example.com'; // The employee to link

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = $this->createFirstAdmin($manager);

        // Store reference
        $this->addReference('admin_superadmin', $admin);

        $manager->flush();
    }

    private function createFirstAdmin(ObjectManager $manager): Admin
    {
        $repoUser = $manager->getRepository(User::class);

        // 1. Admin fixture depends on referenced Employee record
        $masterEmail = 'master@webmaster.com';
        $userMaster = $repoUser->findOneBy(['email' => $masterEmail]);
        if (! $userMaster) {
            throw new \RuntimeException("Employee {$masterEmail} not found.");
        }

        // 2. Admin fixture depends on referenced Employee record
        $refEmail = self::EMPLOYEE_EMAIL;
        $userEmployee = $repoUser->findOneBy(['email' => $refEmail]);
        if (! $userEmployee) {
            throw new \RuntimeException("Employee {$refEmail} not found.");
        }

        $repoEmployee = $manager->getRepository(Employee::class);
        $employee = $repoEmployee->findOneBy(['user' => $userEmployee->getId()]);

        // 3. Find or create Admin
        $email = self::SUPERADMIN_EMAIL;
        $nickname = self::SUPERADMIN_NICKNAME;
        $userRepo = $manager->getRepository(User::class);
        $userAdmin = $userRepo->findOneBy(['email' => $email]);
        if (! $userAdmin) {
            $userAdmin = User::createAdmin(
                id: 0,
                email: Email::fromString($email),
                password: 'temp',
                nickname: $nickname,
                createdByUserId: $userMaster->getId()
            );

            $hashedPassword = $this->passwordHasher->hashPassword($userAdmin, self::SUPERADMIN_PASSWORD);
            $userAdmin->updatePassword($hashedPassword);

            $manager->persist($userAdmin);
            $manager->flush();
        }

        $userAdminId = $userAdmin->getId();
        if (! $userAdminId) {
            throw new \RuntimeException("Admin could not be generated for {$email}");
        }

        // 3. Find or Create Admin
        $repoAdmin = $manager->getRepository(Admin::class);
        $admin = $repoAdmin->findOneBy(['user' => $userAdminId]);
        if (! $admin) {

            $admin = new Admin();
            $admin->setUser($userAdmin);
            $admin->setIsActive(true);
            $admin->setIsBanned(false);
            $admin->setIsSuperadmin(true);
            $admin->setEmployee($employee);

            $manager->persist($admin);
            $manager->flush();

            // 4. Create Admin Profile
            $repoAdminProfile = $manager->getRepository(AdminProfile::class);
            $profile = $repoAdminProfile->findOneBy(['admin' => $admin->getId()]);

            if (! $profile) {
                $profile = AdminProfile::create(
                    admin: $admin,
                    nickname: $nickname
                );

                $manager->persist($profile);
                $manager->flush();
            }
        } else {
            // Admin already exists

            // Ensure it's a superadmin
            if (! $admin->getIsSuperadmin()) {
                $admin->setIsSuperadmin(true);
            }

            // Ensure it's linked to the referenced employee
            if ($employee && ! $admin->getEmployee()) {
                $admin->setEmployee($employee);
            }

            $manager->flush();
        }

        return $admin;
    }

    public function getDependencies(): array
    {
        return [
            \App\Domain\Employee\Fixture\EmployeeFixtures::class, // Need employee to exist first
        ];
    }

    public static function getGroups(): array
    {
        return ['admin', 'users', 'dev'];
    }
}
