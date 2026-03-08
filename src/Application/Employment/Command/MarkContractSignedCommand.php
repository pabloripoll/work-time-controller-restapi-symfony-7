<?php

namespace App\Application\Employment\Command;

readonly class MarkContractSignedCommand
{
    public function __construct(
        public int $contractId,
        public int $adminId
    ) {
    }
}
