<?php

namespace App\Presentation\Http\Rest\Employment;

use App\Application\Employment\Query\GetAllContractTypesQuery;
use App\Application\Employment\Query\GetContractTypeByIdQuery;
use App\Application\Employment\Query\GetContractsByUserQuery;
use App\Application\Employment\Query\GetContractByIdQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/employment')]
class EmploymentContractController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    #[Route('/contract-types', name: 'employment_contract_types_list', methods: ['GET'])]
    public function listContractTypes(): JsonResponse
    {
        $contractTypes = $this->handle(new GetAllContractTypesQuery());
        return $this->json($contractTypes);
    }

    #[Route('/contract-types/{contractTypeId}', name: 'employment_contract_type_show', methods: ['GET'])]
    public function showContractType(int $contractTypeId): JsonResponse
    {
        $contractType = $this->handle(new GetContractTypeByIdQuery($contractTypeId));
        return $this->json($contractType);
    }

    #[Route('/users/{userId}/contracts', name: 'employment_contracts_by_user', methods: ['GET'])]
    public function listContractsByUser(int $userId): JsonResponse
    {
        $contracts = $this->handle(new GetContractsByUserQuery($userId));
        return $this->json($contracts);
    }

    #[Route('/contracts/{contractId}', name: 'employment_contract_show', methods: ['GET'])]
    public function showContract(int $contractId): JsonResponse
    {
        $contract = $this->handle(new GetContractByIdQuery($contractId));
        return $this->json($contract);
    }
}
