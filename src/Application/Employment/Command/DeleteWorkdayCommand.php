<?php

namespace App\Application\Employment\Command;

readonly class DeleteWorkdayCommand
{
    public function __construct(
        public int $workdayId,
        public int $adminId
    ) {
    }
}
