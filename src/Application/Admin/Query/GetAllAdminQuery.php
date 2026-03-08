<?php

declare(strict_types=1);

namespace App\Application\Admin\Query;

readonly class GetAllAdminQuery
{
    public function __construct(
        public ?bool $isActive = null,
        public ?bool $isBanned = null,
        public ?string $orderBy = 'createdAt',
        public ?string $direction = 'DESC'
    ) {
    }
}
