<?php

namespace App\Application\Employment\Query;

class GetContractByIdQuery
{
    public function __construct(
        public readonly int $contractId
    ) {
    }
}
