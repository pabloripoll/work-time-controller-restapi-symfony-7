<?php

declare(strict_types=1);

namespace App\Application\Employee\DTO;

readonly class EmployeeFullDTO
{
    public function __construct(
        public EmployeeDTO $employee,
        public ?EmployeeProfileDTO $profile = null,
        public ?EmployeeContactDTO $contacts = null,
        public ?EmployeeWorkplaceDTO $workplace = null,
        public ?EmployeeGeoLocationDTO $geoLocation = null,
    ) {
    }

    /**
     * Check if employee has complete profile
     */
    public function hasCompleteProfile(): bool
    {
        return $this->profile !== null
            && $this->profile->name !== null
            && $this->profile->surname !== null
            && $this->profile->birthdate !== null
            && $this->contacts?->mobile !== null;
    }

    /**
     * Get full address
     */
    public function getFullAddress(): string
    {
        if (! $this->geoLocation) {
            return '';
        }

        return $this->geoLocation->getFullAddress();
    }
}
