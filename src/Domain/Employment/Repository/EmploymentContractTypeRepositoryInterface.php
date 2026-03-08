<?php

namespace App\Domain\Employment\Repository;

use App\Domain\Employment\Entity\EmploymentContractType;

interface EmploymentContractTypeRepositoryInterface
{
    public function findById(int $id): ?EmploymentContractType;
    
    public function findAll(): array;
    
    public function findAllActive(): array;
    
    public function save(EmploymentContractType $contractType): void;
    
    public function delete(EmploymentContractType $contractType): void;
}
