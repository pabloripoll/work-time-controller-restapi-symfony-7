<?php

declare(strict_types=1);

namespace App\Domain\Employee\Fixture;

use App\Domain\Employee\Entity\Employee;
use App\Domain\Employee\Entity\EmployeeProfile;
use App\Domain\Employee\Entity\EmployeeContact;
use App\Domain\Employee\Entity\EmployeeWorkplace;
use App\Domain\Employee\Entity\EmployeeGeoLocation;
use App\Domain\Geo\Entity\GeoLocation;
use App\Domain\Geo\Fixture\GeoLocationFixtures;
use App\Domain\Office\Entity\Department;
use App\Domain\Office\Entity\Job;
use App\Domain\Office\Fixture\OfficeFixtures;
use App\Domain\Shared\ValueObject\Email;
use App\Domain\User\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EmployeeFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public const TOTAL_EMPLOYEES = 10;

    private Generator $faker;

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
        $this->faker = Factory::create('es_ES'); // Spanish locale for better Spanish names
    }

    public function load(ObjectManager $manager): void
    {
        // Load geographic references (using new unified GeoLocation)
        $europe = $this->getReference(GeoLocationFixtures::CONTINENT_EUROPE, GeoLocation::class);
        $eu = $this->getReference(GeoLocationFixtures::ZONE_EU, GeoLocation::class);
        $spain = $this->getReference(GeoLocationFixtures::COUNTRY_SPAIN, GeoLocation::class);
        $comunitatValenciana = $this->getReference(GeoLocationFixtures::REGION_VALENCIA, GeoLocation::class);
        $valencia = $this->getReference(GeoLocationFixtures::STATE_VALENCIA, GeoLocation::class);

        // Load all department and job references
        $departments = $this->loadDepartmentReferences();
        $jobsByDepartment = $this->loadJobReferences();

        for ($i = 0; $i < self::TOTAL_EMPLOYEES; $i++) {
            $mainAdmin = $i === 0 ? true : false;

            $employee = $this->createEmployee(
                $manager,
                $europe,
                $eu,
                $spain,
                $comunitatValenciana,
                $valencia,
                $departments,
                $jobsByDepartment,
                $mainAdmin
            );

            // Store references
            if ($i < self::TOTAL_EMPLOYEES) {
                $this->addReference("employee_{$i}", $employee);
            }
        }

        $manager->flush();
    }

    private function loadDepartmentReferences(): array
    {
        return [
            'Entry' => $this->getReference(OfficeFixtures::DEPARTMENT_ENTRY, Department::class),
            'Administration' => $this->getReference(OfficeFixtures::DEPARTMENT_ADMINISTRATION, Department::class),
            'Sales' => $this->getReference(OfficeFixtures::DEPARTMENT_SALES, Department::class),
            'Human Resources' => $this->getReference(OfficeFixtures::DEPARTMENT_HR, Department::class),
            'Marketing' => $this->getReference(OfficeFixtures::DEPARTMENT_MARKETING, Department::class),
            'Product Design' => $this->getReference(OfficeFixtures::DEPARTMENT_PRODUCT_DESIGN, Department::class),
            'Software Development' => $this->getReference(OfficeFixtures::DEPARTMENT_SOFTWARE_DEV, Department::class),
        ];
    }

    private function loadJobReferences(): array
    {
        $jobsByDepartment = [];
        $jobTitlesByDepartment = OfficeFixtures::getJobTitlesByDepartment();

        foreach ($jobTitlesByDepartment as $departmentName => $jobTitles) {
            $jobsByDepartment[$departmentName] = [];

            foreach ($jobTitles as $jobTitle) {
                $jobReference = 'job_' . $this->sanitizeReference($departmentName) . '_' . $this->sanitizeReference($jobTitle);
                $job = $this->getReference($jobReference, Job::class);
                $jobsByDepartment[$departmentName][] = $job;
            }
        }

        return $jobsByDepartment;
    }

    private function createEmployee(
        ObjectManager $manager,
        GeoLocation $europe,
        GeoLocation $eu,
        GeoLocation $spain,
        GeoLocation $comunitatValenciana,
        GeoLocation $valencia,
        array $departments,
        array $jobsByDepartment,
        bool $mainAdmin
    ): Employee {


        $firstName = ! $mainAdmin ? $this->faker->firstName() : 'Wortic';
        $lastName = ! $mainAdmin ? $this->faker->lastName() : 'Admin';
        $secondLastName = ! $mainAdmin ? $this->faker->lastName() : '';
        $fullLastName = "{$lastName} {$secondLastName}";

        $normalizedFirstName = $this->sanitizeForEmail($firstName);
        $normalizedLastName = $this->sanitizeForEmail($lastName);

        $emailNumber = ! $mainAdmin ? $this->faker->randomNumber(2) : '';
        $email = strtolower($normalizedFirstName . '.' . $normalizedLastName . $emailNumber . '@example.com');
        $emailVO = Email::fromString($email);

        // Select random department and corresponding job
        $departmentName = $this->faker->randomElement(array_keys($departments));
        $department = $departments[$departmentName];
        $job = $this->faker->randomElement($jobsByDepartment[$departmentName]);

        // 1. Find or Create User
        $userRepo = $manager->getRepository(User::class);
        $existingUser = $userRepo->findOneBy(['email' => $email]);

        if ($existingUser) {
            $user = $existingUser;
        } else {
            $user = User::createEmployee(
                id: 0,
                email: $emailVO,
                password: 'temp',
                nickname: null, // required but not used by employees
                createdByUserId: 1
            );

            $hashedPassword = $this->passwordHasher->hashPassword($user, 'Pass1234');
            $user->updatePassword($hashedPassword);

            $manager->persist($user);
            $manager->flush();
        }

        $userId = $user->getId();
        if (! $userId) {
            throw new \RuntimeException("User ID not generated for {$email}");
        }

        // 2. Find or Create Employee
        $employeeRepo = $manager->getRepository(Employee::class);
        $existingEmployee = $employeeRepo->findOneBy(['user' => $userId]);

        if ($existingEmployee) {
            $employee = $existingEmployee;
        } else {
            $employee = new Employee();
            $employee->setUser($user);
            $employee->setIsActive($this->faker->boolean(95)); // 95% active
            $employee->setIsBanned(false);

            $manager->persist($employee);
            $manager->flush(); // Flush to generate employee ID and UUID
        }

        // 3. Find or Create Employee Profile
        $profileRepo = $manager->getRepository(EmployeeProfile::class);
        $existingProfile = $profileRepo->findOneBy(['employee' => $employee->getId()]);

        if (! $existingProfile) {
            $birthdate = \DateTimeImmutable::createFromMutable(
                $this->faker->dateTimeBetween('-55 years', '-18 years')
            );

            $profile = EmployeeProfile::create(
                employee: $employee,
                name: $firstName,
                surname: $fullLastName,
                birthdate: $birthdate
            );

            $manager->persist($profile);
            $manager->flush();
        }

        // 4. Find or Create Employee Contacts
        $contactsRepo = $manager->getRepository(EmployeeContact::class);
        $existingContacts = $contactsRepo->findOneBy(['employee' => $employee->getId()]);

        if (! $existingContacts) {
            $contacts = EmployeeContact::create(
                employee: $employee,
                postal: $this->faker->postcode(),
                email: $this->faker->boolean(30) ? $this->faker->safeEmail() : null,
                phone: $this->faker->boolean(60) ? '+34 ' . $this->faker->numerify('9## ### ###') : null,
                mobile: '+34 ' . $this->faker->numerify('6## ### ###')
            );

            $manager->persist($contacts);
            $manager->flush();
        }

        // 5. Find or Create Employee Workplace
        $workplaceRepo = $manager->getRepository(EmployeeWorkplace::class);
        $existingWorkplace = $workplaceRepo->findOneBy(['employee' => $employee->getId()]);

        if (! $existingWorkplace) {
            $workplace = EmployeeWorkplace::create(
                employee: $employee,
                department: $department,
                job: $job
            );

            $manager->persist($workplace);
            $manager->flush();
        }

        // 6. Find or Create Employee Geo Location
        $geoLocationRepo = $manager->getRepository(EmployeeGeoLocation::class);
        $existingGeoLocation = $geoLocationRepo->findOneBy(['employee' => $employee->getId()]);

        if (! $existingGeoLocation) {
            $geoLocation = EmployeeGeoLocation::create(
                employee: $employee,
                address: $this->faker->streetAddress()
            );

            $geoLocation->setLocation(
                continent: $europe,
                zone: $eu,
                country: $spain,
                region: $comunitatValenciana,
                state: $valencia,
                district: null,
                city: null,
                suburb: null,
                address: $this->faker->streetAddress()
            );

            $manager->persist($geoLocation);
            $manager->flush();
        }

        return $employee;
    }

    /**
     * Sanitize string for email (remove accents, spaces, special chars)
     */
    private function sanitizeForEmail(string $string): string
    {
        $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
        $string = preg_replace('/[^a-zA-Z0-9]/', '', $string);

        return $string ?? '';
    }

    /**
     * Sanitize string for use in reference keys
     */
    private function sanitizeReference(string $string): string
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $string));
    }

    public function getDependencies(): array
    {
        return [
            GeoLocationFixtures::class,  // Updated to use unified GeoLocation
            OfficeFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['employee', 'dev'];
    }
}
