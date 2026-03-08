<?php

namespace App\Presentation\Request\Admin;

use App\Presentation\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class CreateAdminRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email()
            ],
            'password' => [
                new Assert\NotBlank(),
                new Assert\Length(min: 8)
            ],
            'nickname' => [
                new Assert\NotBlank(),
                new Assert\Length(min: 3, max: 64)
            ]
        ];
    }
}
