<?php

declare(strict_types=1);

namespace App\Domain\Office\Fixture;

use App\Domain\Office\Entity\Department;
use App\Domain\Office\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OfficeFixtures extends Fixture
{
    // Reference constants for easy access
    public const DEPARTMENT_ENTRY = 'department_entry';
    public const DEPARTMENT_ADMINISTRATION = 'department_administration';
    public const DEPARTMENT_SALES = 'department_sales';
    public const DEPARTMENT_HR = 'department_hr';
    public const DEPARTMENT_MARKETING = 'department_marketing';
    public const DEPARTMENT_PRODUCT_DESIGN = 'department_product_design';
    public const DEPARTMENT_SOFTWARE_DEV = 'department_software_dev';

    private array $offices = [
        'departments' => [
            'Entry' => [
                'description' => 'Default department for new employees without assigned department',
                'jobs' => [
                    'Unset' => 'Temporary position for employees awaiting department assignment',
                ],
            ],
            'Administration' => [
                'description' => 'Manages company operations, finances, and administrative tasks',
                'jobs' => [
                    'CEO' => 'Chief Executive Officer responsible for overall company strategy',
                    'Office Manager' => 'Oversees daily office operations and facilities',
                    'Administrative' => 'Provides administrative and clerical support',
                    'Finance Manager' => 'Manages financial planning, budgeting, and reporting',
                ],
            ],
            'Sales' => [
                'description' => 'Drives revenue through customer acquisition and relationship management',
                'jobs' => [
                    'Account Manager' => 'Manages client relationships and accounts',
                    'Sales Representative' => 'Identifies and closes sales opportunities',
                    'Business Developer' => 'Develops new business opportunities and partnerships',
                    'Key Account Manager' => 'Manages strategic high-value client accounts',
                ],
            ],
            'Human Resources' => [
                'description' => 'Manages recruitment, employee relations, and organizational development',
                'jobs' => [
                    'HR Specialist' => 'Handles employee relations and HR processes',
                    'Talent Acquisition' => 'Manages recruitment and talent sourcing',
                    'HR Manager' => 'Oversees HR department and strategic initiatives',
                    'Recruiter' => 'Sources and interviews potential candidates',
                    'Controller' => 'Manages HR compliance and controls',
                ],
            ],
            'Marketing' => [
                'description' => 'Develops and executes marketing strategies to promote company brand',
                'jobs' => [
                    'Marketing Manager' => 'Leads marketing strategy and campaigns',
                    'SEO Specialist' => 'Optimizes website for search engine visibility',
                    'Content Manager' => 'Creates and manages content strategy',
                    'Social Media Manager' => 'Manages social media presence and engagement',
                ],
            ],
            'Product Design' => [
                'description' => 'Creates visual and product design solutions',
                'jobs' => [
                    'Graphic Designer' => 'Creates visual designs and branding materials',
                    'Product Designer' => 'Designs user interfaces and product experiences',
                ],
            ],
            'Software Development' => [
                'description' => 'Builds and maintains software products and infrastructure',
                'jobs' => [
                    'Architect' => 'Designs system architecture and technical strategy',
                    'Tech Lead' => 'Leads technical teams and projects',
                    'UX/UI Designer' => 'Designs user experiences and interfaces',
                    'Frontend Developer' => 'Develops client-side web applications',
                    'Mobile Developer' => 'Develops mobile applications',
                    'Backend Developer' => 'Develops server-side applications and APIs',
                    'DevOps Engineer' => 'Manages infrastructure and deployment pipelines',
                ],
            ],
        ]
    ];

    /**
     * Get all department names for use in other fixtures
     */
    public static function getDepartmentNames(): array
    {
        return [
            'Entry',
            'Administration',
            'Sales',
            'Human Resources',
            'Marketing',
            'Product Design',
            'Software Development',
        ];
    }

    /**
     * Get all job titles mapped by department for use in other fixtures
     */
    public static function getJobTitlesByDepartment(): array
    {
        return [
            'Entry' => ['Unset'],
            'Administration' => ['CEO', 'Office Manager', 'Administrative', 'Finance Manager'],
            'Sales' => ['Account Manager', 'Sales Representative', 'Business Developer', 'Key Account Manager'],
            'Human Resources' => ['HR Specialist', 'Talent Acquisition', 'HR Manager', 'Recruiter', 'Controller'],
            'Marketing' => ['Marketing Manager', 'SEO Specialist', 'Content Manager', 'Social Media Manager'],
            'Product Design' => ['Graphic Designer', 'Product Designer'],
            'Software Development' => ['Architect', 'Tech Lead', 'UX/UI Designer', 'Frontend Developer', 'Mobile Developer', 'Backend Developer', 'DevOps Engineer'],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $departmentReferences = [
            'Entry' => self::DEPARTMENT_ENTRY,
            'Administration' => self::DEPARTMENT_ADMINISTRATION,
            'Sales' => self::DEPARTMENT_SALES,
            'Human Resources' => self::DEPARTMENT_HR,
            'Marketing' => self::DEPARTMENT_MARKETING,
            'Product Design' => self::DEPARTMENT_PRODUCT_DESIGN,
            'Software Development' => self::DEPARTMENT_SOFTWARE_DEV,
        ];

        foreach ($this->offices['departments'] as $departmentName => $departmentData) {
            // Find or create Department
            $department = $this->findOrCreateDepartment(
                $manager,
                $departmentName,
                $departmentData['description']
            );

            // Add department reference
            if (isset($departmentReferences[$departmentName])) {
                $this->addReference($departmentReferences[$departmentName], $department);
            }

            // Create Jobs for this Department
            foreach ($departmentData['jobs'] as $jobTitle => $jobDescription) {
                $job = $this->findOrCreateJob(
                    $manager,
                    $jobTitle,
                    $jobDescription,
                    $department
                );

                // Add job reference (format: job_departmentname_jobtitle)
                $jobReference = 'job_' . $this->sanitizeReference($departmentName) . '_' . $this->sanitizeReference($jobTitle);
                $this->addReference($jobReference, $job);
            }
        }

        $manager->flush();
    }

    /**
     * Find existing department or create new one
     */
    private function findOrCreateDepartment(
        ObjectManager $manager,
        string $name,
        string $description
    ): Department {
        $repo = $manager->getRepository(Department::class);
        $existing = $repo->findOneBy(['name' => $name]);

        if ($existing) {
            return $existing;
        }

        $department = new Department();
        $department->setName($name);
        $department->setDescription($description);
        $manager->persist($department);
        $manager->flush();

        return $department;
    }

    /**
     * Find existing job or create new one
     */
    private function findOrCreateJob(
        ObjectManager $manager,
        string $title,
        string $description,
        Department $department
    ): Job {
        $repo = $manager->getRepository(Job::class);
        $existing = $repo->findOneBy([
            'title' => $title,
            'department' => $department
        ]);

        if ($existing) {
            return $existing;
        }

        $job = new Job();
        $job->setTitle($title);
        $job->setDescription($description);
        $job->setDepartment($department);
        $manager->persist($job);
        $manager->flush();

        return $job;
    }

    /**
     * Sanitize string for use in reference keys
     */
    private function sanitizeReference(string $string): string
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $string));
    }
}
