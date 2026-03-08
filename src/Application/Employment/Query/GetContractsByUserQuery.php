<?php

namespace App\Application\Employment\Query;

class GetContractsByUserQuery
{
    public function __construct(
        public readonly int $userId
    ) {
    }
}
