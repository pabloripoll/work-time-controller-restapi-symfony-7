<?php

namespace App\Application\Employment\Query;

class GetContractTypeByIdQuery
{
    public function __construct(
        public readonly int $contractTypeId
    ) {
    }
}
